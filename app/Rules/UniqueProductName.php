<?php

namespace App\Rules;

use Auth;
use App\Models\Product;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueProductName implements Rule
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
        $findProductNameExist = Product::where('product_name', $value)
            ->where('company_id', $userCompany->company_id)
            ->first();
        
        if ($findProductNameExist)
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
