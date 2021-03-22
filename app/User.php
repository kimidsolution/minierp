<?php

namespace App;

use Storage;
use App\Models\Account;
use App\Models\AccountBalance;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\AccountType;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, HasRoles;

	const STATUS_NEW = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'signature', 'company_id', 'created_by', 'title',
		'job', 'phone_number', 'address'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
	];

	protected $append = [
		'sign_image'
	];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
		'email_verified_at' => 'datetime'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function account_types()
	{
		return $this->hasMany(AccountType::class, 'created_by');
	}

	public function accounts()
	{
		return $this->hasMany(Account::class, 'created_by');
	}

	public function account_balances()
	{
		return $this->hasMany(AccountBalance::class, 'created_by');
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class, 'posted_by');
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class, 'posted_by');
	}

	public function partners()
	{
		return $this->hasMany(Partner::class, 'created_by');
	}

	public function products()
	{
		return $this->hasMany(Product::class, 'created_by');
	}

	public function revenues()
	{
		return $this->hasMany(Revenue::class, 'posted_by');
	}

	public function vouchers()
	{
		return $this->hasMany(Voucher::class, 'posted_by');
	}

	/**
     * Get the sign of user
     * @return string
     */
    public function getSignImageAttribute()
    {
		return (is_null($this->signature)) ? Storage::disk('user_sign')->url('signature.png') : Storage::disk('user_sign')->url($this->signature) ;
	}
}
