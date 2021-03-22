<?php

namespace App\Http\Controllers\Finance;

use File;
use Storage;
use App\Models\Company;
use Stringy\Stringy as S;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ImportFileTransaction;

class TransactionController extends Controller
{
    private function getIsAdmin()
    {
        return Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
    }

    private function getListCompany()
    {
        $list_company = [];
        $isAdmin = $this->getIsAdmin();
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }
        return $list_company;
    }

    private function getValueModelType($model_type)
    {
        if (!is_null($model_type)) {
            if ($model_type == Transaction::MODEL_TYPE_INVOICE_DEC) return Transaction::MODEL_TYPE_INVOICE;
            if ($model_type == Transaction::MODEL_TYPE_VOUCHER_DEC) return Transaction::MODEL_TYPE_VOUCHER;
        }

        return Transaction::MODEL_TYPE_OTHERS;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexReceivable()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $transaction_type = Transaction::TYPE_RECEIVABLE;

        return view('finance.transaction.index', compact('isAdmin', 'list_company', 'transaction_type'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPayable()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $transaction_type = Transaction::TYPE_PAYABLE;

        return view('finance.transaction.index', compact('isAdmin', 'list_company', 'transaction_type'));
    }

    /**
     * Display a form create transaction receivable.
     *
     * @return \Illuminate\Http\Response
     */
    public function createReceivable()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $list_model_type = Transaction::getListTypeOfTransaction();
        $transaction_type = Transaction::TYPE_RECEIVABLE;

        $title_transaction = 'Receivable';
        $value_transaction_type = 0;
        $route_transaction_type = route('finance.transactions.receivable.index');

        return view('finance.transaction.create', compact(
            'isAdmin',
            'list_company',
            'transaction_type',
            'list_model_type',
            'title_transaction',
            'value_transaction_type',
            'route_transaction_type'
        ));
    }

    /**
     * Display a form create transaction Payable.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPayable()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $list_model_type = Transaction::getListTypeOfTransaction();
        $transaction_type = Transaction::TYPE_PAYABLE;

        $title_transaction = 'Payable';
        $value_transaction_type = 1;
        $route_transaction_type = route('finance.transactions.payable.index');

        return view('finance.transaction.create', compact(
            'isAdmin',
            'list_company',
            'transaction_type',
            'list_model_type',
            'title_transaction',
            'value_transaction_type',
            'route_transaction_type'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importIndexTransaction()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();

        return view('finance.transaction.import.index', compact('isAdmin', 'list_company'));
    }

    /**
     * Display a form upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function importUploadViewTransaction()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();

        return view('finance.transaction.import.create', compact('isAdmin', 'list_company'));
    }

    /**
     * Show form upload of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importUploadPostTransaction(Request $request)
    {
        try {

            app()->make('App\Http\Requests\ImportFileTransactionRequest');

            if ($request->has('company_id')) {
                if (!is_null($request->company_id)) {
                    $companyId = $request->company_id;
                }
            } else {
                $companyId = Auth::user()->company_id;
            }

            $company = Company::find($companyId);
            $companyName = $company->company_name;
            $stringy = S::create($companyName);
            $uploadedFile = $request->file('document');
            $fileExtension = $uploadedFile->getClientOriginalExtension();
            $fileName = strtolower('import_' . $stringy->camelize()->toString() .'_' . date('Ymdhis'));
            $fileNameWithExtenstion = $fileName . '.' . $fileExtension;

            Storage::disk('transaction_import')->put(
                $fileNameWithExtenstion,
                File::get($uploadedFile)
            );

            ImportFileTransaction::create([
                'file_name' => $fileNameWithExtenstion,
                'import_status' => ImportFileTransaction::STATUS_HAS_BEEN_UPLOADED,
                'company_id' => $companyId,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name
            ]);

            dispatch(new \App\Jobs\ImportDataTransactionFromExcel(
                $fileNameWithExtenstion,
                $companyId
            ));

            return redirect()->route('finance.transactions.import.index')->with('info', 'File has been uploaded');

        } catch (\ValidationException $e) {
            $messageError = app('array.helper')->getErrorLaravelFirstKey($e->errors());
            return redirect()->back()->with('info', $messageError);
        } catch (\Exception $e) {
            return redirect()->back()->with('info', $e->getMessage());
        }
    }

    /**
     *
     * Display a form edit transaction receivable.
     * @param $id = transaction_id
     *
     * @return \Illuminate\Http\Response
     */
    public function editReceivable($id)
    {
        $isAdmin = $this->getIsAdmin();
        $transaction = Transaction::find($id);
        if (is_null($transaction)) return redirect()->back()->with('info', 'Transaction not found');
        if (!$isAdmin) {
            if ($transaction->company_id != Auth::user()->company_id) {
                return redirect()->back()->with('info', 'You dont have a permission');
            }
        }
        if (
            $transaction->transaction_type != Transaction::TYPE_RECEIVABLE ||
            $transaction->transaction_status != Transaction::STATUS_DRAFT
        ) {
            return redirect()->back()->with('info', 'Edit transaction doesnt match');
        }
        $list_company = $this->getListCompany();
        $list_model_type = Transaction::getListTypeOfTransaction();
        $transaction_type = Transaction::TYPE_RECEIVABLE;

        $title_transaction = 'Receivable';
        $value_transaction_type =  0;
        $route_transaction_type = route('finance.transactions.receivable.index');
        $value_model_type = $this->getValueModelType($transaction->model_type);

        return view('finance.transaction.edit', compact(
            'isAdmin',
            'list_company',
            'transaction_type',
            'list_model_type',
            'transaction',
            'value_model_type',
            'title_transaction',
            'value_transaction_type',
            'route_transaction_type'
        ));
    }

    /**
     *
     * Display a form edit transaction Payable.
     * @param $id = transaction_id
     *
     * @return \Illuminate\Http\Response
     */
    public function editPayable($id)
    {
        $isAdmin = $this->getIsAdmin();
        $transaction = Transaction::find($id);
        if (is_null($transaction)) return redirect()->back()->with('info', 'Transaction not found');
        if (!$isAdmin) {
            if ($transaction->company_id != Auth::user()->company_id) {
                return redirect()->back()->with('info', 'You dont have a permission');
            }
        }
        if (
            $transaction->transaction_type != Transaction::TYPE_PAYABLE ||
            $transaction->transaction_status != Transaction::STATUS_DRAFT
        ) {
            return redirect()->back()->with('info', 'Edit transaction doesnt match');
        }
        $list_company = $this->getListCompany();
        $list_model_type = Transaction::getListTypeOfTransaction();
        $transaction_type = Transaction::TYPE_PAYABLE;

        $title_transaction = 'Payable';
        $value_transaction_type =  1;
        $route_transaction_type = route('finance.transactions.payable.index');
        $value_model_type = $this->getValueModelType($transaction->model_type);

        return view('finance.transaction.edit', compact(
            'isAdmin',
            'list_company',
            'transaction_type',
            'list_model_type',
            'transaction',
            'value_model_type',
            'title_transaction',
            'value_transaction_type',
            'route_transaction_type'
        ));
    }
}
