<?php

use App\Models\Company;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
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
            $categories = config('sempoa.product.category');

            foreach ($companies as $company) {
                foreach ($categories as $category) {
                    ProductCategory::create([
                        'category_name' => $category,
                        'company_id'    => $company->id,
                        'created_at'    => now(),
                        'created_by'    => 'System',
                        'updated_at'    => now(),
                        'updated_by'    => 'System'
                    ]);
                }
            }
        }
    }
}
