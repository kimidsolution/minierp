<?php

namespace App\Imports;

use DB;
use Str;
use App\Models\Account;
use App\Models\AccountBalance;
use App\Models\company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountImport implements ToCollection, WithHeadingRow
{
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $data_receive = $this->data;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                // update rule 19 Nov 20
                // create account if company type is umkm,
                // but type enterprise handling with other format by imported format page
                if ($data_receive['company_type'] == company::TYPE_UMKM) {
                    //? first check parent account
                    $parentAccount = null;
                    if ($row['parent_account'] !== null) {
                        $parentAccount = Account::where('company_id', $data_receive['company_id'])
                            ->where('account_code', $row['parent_account'])
                            ->first();
                    }

                    //? create account
                    $account = Account::create([
                        'parent_account_id' => $parentAccount == null ? null : $parentAccount->id,
                        'company_id'        => $data_receive['company_id'],
                        'account_name'      => trim($row['account_name']),
                        'account_code'      => $row['account_no'],
                        'level'             => $row['level'],
                        'balance'           => strtolower($row['saldo_normal']),
                        'account_type'      => $row['type_id'],
                        'created_by'        => $data_receive['user_name'],
                        'updated_by'        => $data_receive['user_name']
                    ]);

                    //? create account balance
                    AccountBalance::create([
                        'account_id'    => $account->id,
                        'is_closed'     => false,
                        'balance_date'  => date("Y-m-d", strtotime(now())),
                        'debit_amount'  => 0,
                        'credit_amount' => 0,
                        'created_by'    => $data_receive['user_name'],
                        'updated_by'    => $data_receive['user_name']
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                dd($errors);
            }
            dd($e->getMessage());
        }
    }

    public function headingRow(): int
    {
        return 5;
    }
}
