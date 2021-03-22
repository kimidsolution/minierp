<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Storage;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticable;

/**
 * Class User
 *
 * @property int $id
 * @property int $company_id
 * @property int|null $pic_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property int $status
 * @property string|null $title
 * @property string|null $job
 * @property string|null $phone_number
 * @property string|null $sign
 * @property string|null $address
 * @property string|null $remember_token
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Collection|AccountType[] $account_types
 * @property Collection|Account[] $accounts
 * @property Collection|AccountBalance[] $account_balances
 * @property Collection|Expense[] $expenses
 * @property Collection|Invoice[] $invoices
 * @property Collection|Partner[] $partners
 * @property Collection|Product[] $products
 * @property Collection|Revenue[] $revenues
 * @property Collection|UsersCompany[] $users_companies
 * @property Collection|Voucher[] $vouchers
 *
 * @package App\Models
 */
class User extends Authenticable
{
	const STATUS_NEW = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;

	use SoftDeletes, HasRoles;
	protected $table = 'users';

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'company_id',
		'pic_id',
		'name',
		'email',
		'email_verified_at',
		'password',
		'status',
		'title',
		'job',
		'phone_number',
		'signature',
		'address',
		'remember_token',
		'created_by',
		'updated_by',
		'deleted_by'
	];

	protected $append = [
		'sign_image'
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

	public function users_companies()
	{
		return $this->hasMany(UsersCompany::class);
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
