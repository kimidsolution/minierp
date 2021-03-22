<?php

namespace App\Rules;

use Auth;
use App\Models\Product;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueProductSku implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $userCompany = User::where('id', Auth::user()->id)->first();
        $findProductSkuExist = Product::where('sku', $value)->where('company_id', $userCompany->company_id)->first();
        
        if ($findProductSkuExist)
            return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Product :attribute already exist.';
    }
}
