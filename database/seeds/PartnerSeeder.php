<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ('local' == config('app.env')) {
            $company = \App\Models\Company::where('company_name', 'PT Kapzet Teknologi Informasi')->first();
            $partner = \App\Models\Partner::create([
                'id'                => Str::uuid(),
                'partner_name'      => 'PT Citra Persada Infrastruktur',
                'email'             => 'info@citrapersada.netx',
                'phone_number'      => '021 11223366',
                'address'           => 'Kemayoran',
                'tax_id_number'     => '',
                'city'              => 'Jakarta Pusat',
                'country'           => 'Indonesia',
                'pic_name'          => 'Hidayat',
                'pic_email'         => 'hidayat@citrapersada.netx',
                'pic_phone_number'  => '021 11223366',
                'is_vendor'         => false,
                'is_client'         => true,
                'company_id'        => $company->id,
                'created_by'        => 'System',
                'updated_by'        => 'System',
                'created_at'        => now(),
                'updated_at'        => now()
            ]);

            $company_other = \App\Models\Company::where('company_name', 'CV Warung Sejahtera')->first();
            $partner_other = \App\Models\Partner::create([
                'id'                => Str::uuid(),
                'partner_name'      => 'General Customer',
                'email'             => 'customer@customer.com',
                'phone_number'      => '',
                'address'           => 'Jakarta',
                'tax_id_number'     => '',
                'city'              => 'Jakarta',
                'country'           => 'Indonesia',
                'pic_name'          => '',
                'pic_email'         => '',
                'pic_phone_number'  => '',
                'is_vendor'         => false,
                'is_client'         => true,
                'company_id'        => $company_other->id,
                'created_by'        => 'System',
                'updated_by'        => 'System',
                'created_at'        => now(),
                'updated_at'        => now()
            ]);
        }

        // foreach ($company as $value) {
        //     for ($i = 0; $i < 5 ; $i++) {

        //         $faker = \Faker\Factory::create();
        //         $rand_boolean = (bool)random_int(0, 1);
        //         $next_rand_boolean = $rand_boolean == false ? true : false;

        //         Partner::create([
        //             'id' => (string) Str::uuid(),
        //             'name' => $faker->unique()->company,
        //             'email' => $faker->unique()->companyEmail,
        //             'phone_number' => $faker->unique()->phoneNumber,
        //             'address' => $faker->unique()->address,
        //             'tax_id_number' => '-',
        //             'city' => 'Jakarta',
        //             'country' => 'Indonesia',
        //             'pic_name' => $faker->unique()->name,
        //             'pic_email' => $faker->unique()->email,
        //             'pic_phone_number' => $faker->unique()->phoneNumber,
        //             'is_vendor' => $rand_boolean,
        //             'is_client' => $next_rand_boolean,
        //             'company_id' => $value->id,
        //             'created_by' => User::where('company_id', $value->id)->first()->name,
        //             'updated_by' => User::where('company_id', $value->id)->first()->name,
        //             'created_at' => now(),
        //             'updated_at' => now()
        //         ]);
        //     }
        // }
    }
}