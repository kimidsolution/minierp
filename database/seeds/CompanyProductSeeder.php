<?php

use App\User;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CompanyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ('local' == config('app.env')) {

            // Create product for Kapzet
            $kapzet = Company::where('company_name', 'PT Kapzet Teknologi Informasi')->first();
            $service = ProductCategory::where('company_id', $kapzet->id)
                ->where('category_name', 'Service')
                ->first();
            Product::create([
                'id'            => Str::uuid(),
                'product_name'  => 'Jasa Konsultan CTO',
                'sku'           => 'Monthly',
                'price'         => 15000000,
                'status'        => Product::STATUS_ACTIVE,
                'type'          => Product::TYPE_SERVICE,
                'product_category_id'   => $service->id,
                'company_id'    => $kapzet->id,
                'created_by'    => 'System',
                'created_at'    => now(),
                'updated_by'    => 'System',
                'updated_at'    => now()
            ]);

            // Create product for Warung
            $warung = Company::where('company_name', 'CV Warung Sejahtera')->first();
            $food = ProductCategory::where('company_id', $warung->id)
                ->where('category_name', 'Food & Beverage')
                ->first();
            Product::create([
                'id'            => Str::uuid(),
                'product_name'  => 'Indomie',
                'sku'           => 'pcs',
                'price'         => 3000,
                'status'        => Product::STATUS_ACTIVE,
                'type'          => Product::TYPE_GOODS,
                'product_category_id'   => $food->id,
                'company_id'    => $warung->id,
                'created_by'    => 'System',
                'created_at'    => now(),
                'updated_by'    => 'System',
                'updated_at'    => now()
            ]);
        }
    }
}
