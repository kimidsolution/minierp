<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionExcelCoaSheetImport implements ToCollection, WithMultipleSheets, WithHeadingRow
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
            'COA' => $this,
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


        $validateRowMustBeFill = ['account_number', 'account_type', 'account_level', 'account_name'];


        foreach ($rows as $key => $row) {
                
            $mustBreak = false;
            $columnNull = null;

            foreach ($validateRowMustBeFill as $value) {
                $rowArray = $row->toArray();

                if (false == array_key_exists($value, $rowArray))
                    throw new \Exception('Column ' . $value . ' harus ada pada sheet coa');


                if (is_null($rowArray[$value]) || '' == $rowArray[$value]) {
                    $mustBreak = true;
                    $columnNull = $value;
                    break;
                }
            }


            if (true == $mustBreak)
                throw new \Exception('Row ke - ' . $key . ' pada column ' . $columnNull . ' isinya kosong pada sheet coa');


            $account = new Account;
            $accountConstants = $account->getConstants();
            $type = trim($row['account_type']);
            $level = trim($row['account_level']);
            $accountName = trim($row['account_name']);
            $accountNumber = trim($row['account_number']);
            $accountNumberParent = trim($row['account_number_parent']);


            if (!array_key_exists($type, $accountConstants))
                throw new \Exception('Account type tidak terdaftar dalam sistem');


            $accountTypeValue = $accountConstants[$type];
            $accountCodeExist = Account::where('account_code', $accountNumber)->where('company_id', $this->companyId)->first();
            $accountNameExist = Account::where('account_name', $accountName)->where('company_id', $this->companyId)->first();


            if ($accountCodeExist)
                throw new \Exception('Nomor akun ' . $accountNumber . ' sudah ada' );


            if ($accountNameExist)
                throw new \Exception('Nama akun ' . $accountName . ' sudah ada' );


            // get balance account
            $listCoaType = config('sempoa.coa_type');
            foreach ($listCoaType as $keyC => $valueC) {
                if ($valueC['id'] == $accountTypeValue) {
                    $balance = $listCoaType[$keyC]['balance'];
                    $break;
                }
            }


            $createNewAccount = [
                'companyId' => $company->id,
                'account_name' => $accountName,
                'account_code' => $accountNumber,
                'level' => $level,
                'balance' => $balance,
                'account_type' => $accountTypeValue,
                'company_id' => $company->id
            ];


            if (!is_null($accountNumberParent)) {
                $parentAccount = Account::where('account_name', $accountNumberParent)->where('company_id', $company->id)->first();
                if ($parentAccount) {
                    $createNewAccount['parent_account_id'] = $parentAccount->id;
                }
            }


            Account::create($createNewAccount);
        }
    }
}
