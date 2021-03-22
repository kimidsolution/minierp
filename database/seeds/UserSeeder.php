<?php

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::where('company_name', 'PT Sempoa Prima Teknologi')->first();

        $superAdmin = User::create([
            'company_id' => $company->id,
            'name' => 'Super Admin',
            'email' => 'superadmin@sempoa.id',
            'email_verified_at' => now(),
            'status' => User::STATUS_ACTIVE,
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'company_id' => $company->id,
            'name' => 'Admin',
            'email' => 'admin@sempoa.id',
            'email_verified_at' => now(),
            'status' => User::STATUS_ACTIVE,
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin->assignRole('Ops Admin');

        $ceo = User::create([
            'company_id' => $company->id,
            'name' => 'Ivan Tanu',
            'email' => 'ivan@sempoa.id',
            'email_verified_at' => now(),
            'status' => User::STATUS_ACTIVE,
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ceo->assignRole('Company Admin');
        $company->pic_id = $ceo->id;
        $company->save();

        if ('local' == config('app.env')) {
            $company = Company::where('company_name', 'PT Kapzet Teknologi Informasi')->first();
            $companyAdmin = User::create([
                'company_id' => $company->id,
                'name' => 'Ivan Tanu',
                'email' => 'ivan@kapzet.id',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_by' => 'System',
                'updated_by' => 'System',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $companyAdmin->assignRole('Company Admin');
            $company->pic_id = $companyAdmin->id;
            $company->save();

            $warung = Company::where('company_name', 'CV Warung Sejahtera')->first();
            $warungUser = User::create([
                'company_id' => $warung->id,
                'name' => 'Bambang',
                'email' => 'bambang@test.com',
                'email_verified_at' => now(),
                'status' => User::STATUS_ACTIVE,
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_by' => 'System',
                'updated_by' => 'System',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $warungUser->assignRole('Company Admin');
            $warung->pic_id = $warungUser->id;
            $warung->save();

            // $companies = Company::get();
            // foreach ($companies as $company) {
            //     if ($company->brand_name != 'Sempoa.id') {
            //         $email = substr($company->email, 4);
            //         $companyAdmin = User::create([
            //             'company_id' => $company->id,
            //             'name' => 'Alice',
            //             'email' => 'alice'.$email,
            //             'email_verified_at' => now(),
            //             'status' => User::STATUS_ACTIVE,
            //             'password' => bcrypt('12345678'),
            //             'remember_token' => Str::random(10),
            //             'created_by' => 'Seeder',
            //             'updated_by' => 'Seeder',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ]);

            //         $companyAdmin->assignRole('Company Admin');
            //         $company->pic_id = $companyAdmin->id;
            //         $company->save();
            //     }
            // }
        }
    }
}
