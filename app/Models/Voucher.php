<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Voucher
 *
 * @property string $id
 * @property string $company_id
 * @property string|null $partner_id
 * @property string $payment_account_id
 * @property Carbon $voucher_date
 * @property int $voucher_type
 * @property string $voucher_number
 * @property string|null $note
 * @property int $is_posted
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Partner $partner
 * @property Account $account
 * @property Collection|VoucherDetail[] $voucher_details
 *
 * @package App\Models
 */
class Voucher extends Model
{
	use SoftDeletes;

	const TYPE_RECEIVABLE = 1;
	const TYPE_PAYABLE = 2;

	const POSTED_YES = 1;
	const POSTED_NO = 2;

	protected $table = 'vouchers';
	public $incrementing = false;

	protected $casts = [
		'voucher_type' => 'int',
		'is_posted' => 'int'
	];

	protected $dates = [
		'voucher_date'
	];

	protected $fillable = [
		'company_id',
		'partner_id',
		'payment_account_id',
		'voucher_date',
		'voucher_type',
		'voucher_number',
		'note',
		'is_posted',
		'created_by',
		'updated_by',
		'deleted_by'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function partner()
	{
		return $this->belongsTo(Partner::class);
	}

	public function account()
	{
		return $this->belongsTo(Account::class, 'payment_account_id');
	}

	public function voucher_details()
	{
		return $this->hasMany(VoucherDetail::class);
    }

    public function invoices()
	{
		return $this->belongsToMany(Invoice::class, 'voucher_details')
					->withPivot('id', 'amount', 'payment_status', 'deleted_at')
					->withTimestamps();
	}

	public static function getTypeOfVoucher($idType) {
		switch ($idType)
		{
			case self::TYPE_RECEIVABLE :
				return 'Receivable';
				break;
			case self::TYPE_PAYABLE :
				return 'Payable';
				break;
			default :
			return 'Receivable';
		}
	}

	public static function getTypeColorVoucher($idStatus) {
		switch ($idStatus)
		{
			case self::TYPE_RECEIVABLE :
				return 'pink';
				break;
			case self::TYPE_PAYABLE :
				return 'secondary';
				break;
			default :
			return 'secondary';
		}
	}

	public static function getPostedOfVoucher($idType) {
		switch ($idType)
		{
			case self::POSTED_YES :
				return 'Yes';
				break;
			case self::POSTED_NO :
				return 'No';
				break;
			default :
			return 'No';
		}
	}

	public static function getPostedColorVoucher($idStatus) {
		switch ($idStatus)
		{
			case self::POSTED_YES :
				return 'success';
				break;
			case self::POSTED_NO :
				return 'danger';
				break;
			default :
			return 'danger';
		}
	}

	protected static function boot()
    {
		parent::boot();

        static::creating(function ($model) {
			$model->id = (string) Str::uuid();
			$model->created_at = now();
			$model->updated_at = now();
        });
    }
}
