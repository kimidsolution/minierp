<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 *
 * @property int $id
 * @property string $company_name
 * @property string|null $brand_name
 * @property string $email
 * @property string $phone_number
 * @property string $address
 * @property string|null $logo
 * @property string|null $tax_id_number
 * @property string|null $fax
 * @property string|null $website
 * @property bool $vat_enabled
 * @property int $status
 * @property int $type
 * @property string $currency_id
 * @property string $city
 * @property string $country
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $pic_id
 *
 * @property Currency $currency
 * @property User $user
 * @property Collection|Account[] $accounts
 * @property Collection|Currency[] $currencies
 * @property Collection|Expense[] $expenses
 * @property Collection|Invoice[] $invoices
 * @property Collection|Partner[] $partners
 * @property Collection|ProductCategory[] $product_categories
 * @property Collection|Product[] $products
 * @property Collection|TransactionTemp[] $transaction_temps
 * @property Collection|Transaction[] $transactions
 * @property Collection|User[] $users
 * @property Collection|Voucher[] $vouchers
 *
 * @package App\Models
 */
class Company extends Model
{
	use SoftDeletes;

	const STATUS_NEW = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_ONHOLD = 3;
	const STATUS_DELETED = 4;

	const TYPE_UMKM = 1;
	const TYPE_ENTERPRISE = 2;

	protected $table = 'companies';
    public $incrementing = false;
    protected $keyType = 'string';

	protected $casts = [
		'vat_enabled' => 'bool',
		'status' => 'int',
		'type' => 'int',
		'pic_id' => 'int'
	];

	protected $fillable = [
		'company_name',
		'brand_name',
		'email',
		'phone_number',
		'address',
		'logo',
		'tax_id_number',
		'fax',
		'website',
		'vat_enabled',
		'status',
		'type',
		'currency_id',
		'city',
		'country',
		'created_by',
		'updated_by',
		'deleted_by',
		'pic_id'
	];

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function pic()
	{
		return $this->belongsTo(User::class, 'pic_id');
	}

	public function accounts()
	{
		return $this->hasMany(Account::class);
	}

	public function currencies()
	{
		return $this->belongsToMany(Currency::class);
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function partners()
	{
		return $this->hasMany(Partner::class);
	}

	public function product_categories()
	{
		return $this->hasMany(ProductCategory::class);
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function vouchers()
	{
		return $this->hasMany(Voucher::class);
	}

	public static function getStatusCompany($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_NEW :
				return 'New';
				break;
			case self::STATUS_ACTIVE :
				return 'Active';
				break;
			case self::STATUS_INACTIVE :
				return 'Inactive';
				break;
			case self::STATUS_ONHOLD :
				return 'Onhold';
				break;
			case self::STATUS_DELETED :
				return 'Deleted';
				break;
			default :
			return 'New';
		}
	}

	public static function validateStatusCompany($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_NEW :
				return true;
				break;
			case self::STATUS_ACTIVE :
				return true;
				break;
			case self::STATUS_INACTIVE :
				return true;
				break;
			case self::STATUS_ONHOLD :
				return true;
				break;
			case self::STATUS_DELETED :
				return true;
				break;
			default :
			return false;
		}
	}

	public static function getStatusColorCompany($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_NEW :
				return 'dark';
				break;
			case self::STATUS_ACTIVE :
				return 'success';
				break;
			case self::STATUS_INACTIVE :
				return 'warning';
				break;
			case self::STATUS_ONHOLD :
				return 'warning';
				break;
			case self::STATUS_DELETED :
				return 'danger';
				break;
			default :
			return 'dark';
		}
	}

	public static function getTypeOfCompany($idType) {
		switch ($idType)
		{
			case self::TYPE_UMKM :
				return 'UMKM';
				break;
			case self::TYPE_ENTERPRISE :
				return 'ENTERPRISE';
				break;
			default :
			return 'UMKM';
		}
	}

	public static function getTypeColorCompany($idStatus) {
		switch ($idStatus)
		{
			case self::TYPE_UMKM :
				return 'pink';
				break;
			case self::TYPE_ENTERPRISE :
				return 'secondary';
				break;
			default :
			return 'secondary';
		}
	}

	protected static function boot()
    {
      parent::boot();

      $userName = '';

      if (!is_null(Auth::user())) {
        $userName = Auth::user()->name;
      } elseif (app('request')->has('user_id')) {
        $user = User::find(app('request')->user_id);
        if ($user) {
          $userName = $user->name;
        }
      }

      // for create
      static::creating(function ($model) use ($userName) {
          $model->id = (string) Str::uuid();
          $model->created_by = $userName;
          $model->updated_by = $userName;
      });

      static::saving(function ($model) use ($userName) {
          $model->updated_at = now();
          $model->updated_by = $userName;
      });

      static::deleting(function($model) use ($userName) {
          $model->deleted_at = now();
          $model->deleted_by = $userName;
          $model->save();
      });
    }
}
