<?php

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rupiah = \App\Models\Currency::where('currency_name', 'Indonesian Rupiah')->first();

        $sempoa = Company::create([
            'company_name'  => 'PT Sempoa Prima Teknologi',
            'brand_name'    => 'Sempoa.id',
            'email'         => 'info@sempoa.id',
            'phone_number'  => '(021) x123456',
            'address'       => 'Komplek Green Ville Blok BG No. 68, Jakarta 11510',
            'city'          => 'Jakarta',
            'country'       => 'Indonesia',
            'tax_id_number' => 'x123450123456789',
            'website'       => 'https://sempoa.id',
            'vat_enabled'   => true,
            'status'        => Company::STATUS_ACTIVE,
            'type'          => Company::TYPE_ENTERPRISE,
            'currency_id'   => $rupiah->id,
            'created_by'    => 'System',
            'updated_by'    => 'System',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);
        $sempoa->currencies()->attach($rupiah->id);

        if ('local' == config('app.env')) {

            $kapzet = Company::create([
                'company_name'  => 'PT Kapzet Teknologi Informasi',
                'brand_name'    => 'Kapzet IT Consulting',
                'email'         => 'info@kapzet.id',
                'phone_number'  => '(021) 11223344',
                'address'       => 'Pusat Bisnis Thamrin City Unit OS15B, Gedung Thamrin City',
                'city'          => 'Jakarta Pusat',
                'country'       => 'Indonesia',
                'tax_id_number' => '941451254072001',
                'website'       => 'https://kapzet.id',
                'vat_enabled'   => false,
                'status'        => Company::STATUS_ACTIVE,
                'type'          => Company::TYPE_UMKM,
                'currency_id'   => $rupiah->id,
                'created_by'    => 'System',
                'updated_by'    => 'System',
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
            $kapzet->currencies()->attach($rupiah->id);

            $warung = Company::create([
                'company_name'  => 'CV Warung Sejahtera',
                'brand_name'    => 'Warung Sejahtera',
                'email'         => '',
                'phone_number'  => '(021) 11223355',
                'address'       => 'Jl. Duri Raya',
                'city'          => 'Jakarta Barat',
                'country'       => 'Indonesia',
                'tax_id_number' => '',
                'website'       => '',
                'vat_enabled'   => false,
                'status'        => Company::STATUS_ACTIVE,
                'type'          => Company::TYPE_UMKM,
                'currency_id'   => $rupiah->id,
                'created_by'    => 'System',
                'updated_by'    => 'System',
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
            $warung->currencies()->attach($rupiah->id);
        }
    }
}
