<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * 
 * @property string $id
 * @property int $company_id
 * @property int $product_category_id
 * @property string $product_name
 * @property string $sku
 * @property float $price
 * @property int $type
 * @property string|null $logo
 * @property string|null $description
 * @property int $status
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Company $company
 * @property ProductCategory $product_category
 * @property Collection|InvoiceDetail[] $invoice_details
 *
 * @package App\Models
 */
class Product extends Model
{
	use SoftDeletes;

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;	
	const STATUS_DELETED = 4;

	const TYPE_GOODS = 1;
	const TYPE_SERVICE = 2;

	protected $table = 'products';
	public $incrementing = false;

	protected $casts = [
		'price' => 'float',
		'type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'company_id',
		'product_category_id',
		'product_name',
		'sku',
		'price',
		'type',
		'logo',
		'description',
		'status',
		'created_by',
		'updated_by',
		'deleted_by'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function product_category()
	{
		return $this->belongsTo(ProductCategory::class);
	}

	public function invoice_details()
	{
		return $this->hasMany(InvoiceDetail::class);
	}

	
	public static function getTypeOfProduct($idType) {
		switch ($idType)
		{
			case self::TYPE_GOODS :
				return 'Goods';
				break;
			case self::TYPE_SERVICE :
				return 'Service';
				break;
			default : 
			return 'Goods';
		}
	}

	public static function getStatusOfProduct($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_ACTIVE :
				return 'Active';
				break;
			case self::STATUS_INACTIVE :
				return 'Inactive';
				break;
			case self::STATUS_DELETED :
				return 'Deleted';
				break;
			default : 
			return 'Active';
		}
	}

	public static function getStatusColorProduct($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_ACTIVE :
				return 'success';
				break;
			case self::STATUS_INACTIVE :
				return 'warning';
				break;
			case self::STATUS_DELETED :
				return 'danger';
				break;
			default : 
			return 'success';
		}
	}

	public static function getTypeColorProduct($idStatus) {
		switch ($idStatus)
		{
			case self::TYPE_SERVICE :
				return 'pink';
				break;
			case self::TYPE_GOODS :
				return 'secondary';
				break;
			default : 
			return 'secondary';
		}
	}

	public static function validateStatusProduct($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_ACTIVE :
				return true;
				break;
			case self::STATUS_INACTIVE :
				return true;
				break;
			case self::STATUS_DELETED :
				return true;
				break;
			default : 
			return false;
		}		
	}

	protected static function boot()
    {
		parent::boot();
        $user_id = app('request')->user_id;
        $user = !is_null($user_id) ? User::find($user_id) : Auth::user();
        $user_name = !is_null($user) ? $user->name : '';

        static::creating(function($model) use ($user_name) {
            $model->id = (string) Str::uuid();
            $model->created_by = $user_name;
            $model->updated_by = $user_name;
		});

		static::saving(function ($model) use ($user_name) {
            $model->updated_at = now();
			$model->updated_by = $user_name;
        });

        static::deleting(function($model) use ($user_name) {
            $model->deleted_at = now();
            $model->deleted_by = $user_name;
            $model->save();
        });
    }
}
