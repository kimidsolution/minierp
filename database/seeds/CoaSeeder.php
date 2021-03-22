<?php

use App\Models\Company;
use App\Imports\AccountImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ('local' == config('app.env')) {
            $companies = Company::get();

            foreach ($companies as $company) {

                //? do create account & account balance default based on type company
                $data_send = [
                    'company_id' => $company->id,
                    'company_type' => $company->type,
                    'user_name' => 'System',
                ];

                Excel::import(new AccountImport($data_send), public_path('COA_Template_Sempoa_Update.xlsx'));
            }
        }
    }
}
