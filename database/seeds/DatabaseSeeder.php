<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CurrencySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CoaSeeder::class);
        $this->call(FinanceConfigurationSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(CompanyProductSeeder::class);
        $this->call(PartnerSeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(VoucherSeeder::class);
    }
}
