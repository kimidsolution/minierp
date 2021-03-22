<?php

namespace App\Services\Api\Datatable\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ImportFileTransaction;
use Yajra\DataTables\Facades\DataTables;

class TransactionImportService
{
    public function handle(Request $request)
    {
        $get_data = ImportFileTransaction::with(['company']);
        $get_data->where('company_id', $request->company_id ? $request->company_id : null);
        $transaction = $get_data->orderBy('updated_at', 'DESC')->get();

        return DataTables::of($transaction)
            ->addColumn('status', function ($transaction) {
                $statusName = null;
                $status = $transaction->import_status;

                switch ($status) {
                    case ImportFileTransaction::STATUS_ON_PROGRESS_FETCH_DATA:
                        $statusName = 'Penarikan Data Sedang Diproses';
                        break;
                    case ImportFileTransaction::STATUS_DATA_HAS_BEEN_RECORDED:
                        $statusName = 'Proses Penarikan Data Selesai';
                        break;
                    default:
                        $statusName = 'Belum Diproses';
                        break;
                }

                return $statusName;
            })
            ->addColumn('action', function ($transaction) {
                return view('datatable.transaction.link-action-import', compact('transaction'));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
