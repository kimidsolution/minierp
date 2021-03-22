<?php

use App\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listCurrencies = [
            [
                'id' => Str::uuid(),
                'currency_name' => 'Australian Dollar',
                'currency_code' => 'AUD',
                'iso_code' => '032',
                'symbol' => '$',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'id' => Str::uuid(),
                'currency_name' => 'Chinese Yuan',
                'currency_code' => 'CNY',
                'iso_code' => '156',
                'symbol' => '¥',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'id' => Str::uuid(),
                'currency_name' => 'European Euro',
                'currency_code' => 'EUR',
                'iso_code' => '978',
                'symbol' => '€',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'id' => Str::uuid(),
                'currency_name' => 'Indonesian Rupiah',
                'currency_code' => 'IDR',
                'iso_code' => '360',
                'symbol' => 'Rp',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'id' => Str::uuid(),
                'currency_name' => 'Japanese Yen',
                'currency_code' => 'JPY',
                'iso_code' => '392',
                'symbol' => '¥',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'id' => Str::uuid(),
                'currency_name' => 'US Dollar',
                'currency_code' => 'USD',
                'iso_code' => '840',
                'symbol' => '$',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 'System',
                'updated_by' => 'System',
            ]
        ];

        Currency::insert($listCurrencies);
    }
}
