<?php

use App\Configurations\Finance\FinanceConfigurationDefault;
use App\Models\Company;
use Illuminate\Database\Seeder;

class FinanceConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = Company::whereNull('deleted_at')
            ->where('status', Company::STATUS_ACTIVE)
            ->where('type', Company::TYPE_UMKM)
            ->orderBy('company_name', 'asc')
            ->get();

        $list_company = $data->toArray();

        if (count($list_company) > 0) {
            foreach ($list_company as $value) {
                $configuration = new FinanceConfigurationDefault($value['id']);
                $configuration->execute();
            }
        }
    }
}
