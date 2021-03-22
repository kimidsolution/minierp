<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionExcelJournalSheetImport implements ToCollection, WithMultipleSheets, WithHeadingRow
{
    private $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            'Transaction' => $this,
        ];
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $company = Company::where('id', $this->companyId)->first();
        if (is_null($company))
            throw new \Exception('Perusahaan tidak ditemukan');
            
        
        $validateRowMustBeFill = [
            'date', 'description', 'account_number_debit',
            'amount_debit', 'account_number_credit', 'amount_credit', 'transaction_type'
        ];


        foreach ($rows as $key => $row) {

            $mustBreak = false;
            $columnNull = null;

            foreach ($validateRowMustBeFill as $value) {
                $rowArray = $row->toArray();

                if (false == array_key_exists($value, $rowArray))
                    throw new \Exception('Column ' . $value . ' harus ada pada sheet transaction');


                if (is_null($rowArray[$value])) {
                    $mustBreak = true;
                    $columnNull = $value;
                    break;
                }
            }


            if (true == $mustBreak)
                throw new \Exception('Row ke - ' . $key . ' pada column ' . $columnNull . ' isinya kosong pada sheet transaction');


            $transactionType = $row['transaction_type'];
            $transactionStatus = Transaction::STATUS_POSTED;
            $referenceNumber = $row['reference_number'];
            $description = $row['description'];
            $amountDebit = $row['amount_debit'];
            $amountCredit = $row['amount_credit'];
            $accountNumberDebit = $row['account_number_debit'];
            $accountNumberCredit = $row['account_number_credit'];
            $transactionDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']);


            // create transaction detail
            $accountDebit = Account::where('company_id', $this->companyId)->where('account_code', $accountNumberDebit)->first();
            $accountCredit = Account::where('company_id', $this->companyId)->where('account_code', $accountNumberCredit)->first();


            if (is_null($accountCredit))
                throw new \Exception('Account redit tidak ditemukan');


            if (is_null($accountDebit))
                throw new \Exception('Account debit tidak ditemukan');


            // check reference number
            $transactionExist = null;

            if (!is_null($referenceNumber)) {
                $transactionExist = Transaction::where('reference_number', $referenceNumber)->first();
                if (!is_null($transactionExist)) {
                    $transaction = $transactionExist;
                }
            }  


            if (is_null($transactionExist)) {
                $transaction = Transaction::create([
                    'transaction_date' =>  $transactionDate->format('Y-m-d'),
                    'transaction_type' => $transactionType,
                    'transaction_status' => $transactionStatus,
                    'model_type' => Transaction::MODEL_TYPE_OTHERS,
                    'reference_number' => $referenceNumber,
                    'description' => (true == is_null($description)) ? 'Unknown' : $description,
                    'company_id' => $this->companyId
                ]);
            }


            // debit
            TransactionDetail::create([
                'account_id' => $accountDebit->id,
                'transaction_id' => $transaction->id,
                'debit_amount' => (double) $amountDebit,
                'credit_amount' => 0
            ]);


            // credit
            TransactionDetail::create([
                'account_id' => $accountCredit->id,
                'transaction_id' => $transaction->id,
                'debit_amount' => 0,
                'credit_amount' => (double) $amountCredit
            ]);
        }
    }
}
