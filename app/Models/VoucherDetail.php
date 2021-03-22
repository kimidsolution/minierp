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
 * Class VoucherDetail
 *
 * @property string $id
 * @property string $voucher_id
 * @property string|null $invoice_id
 * @property float $amount
 * @property int $payment_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Invoice $invoice
 * @property Voucher $voucher
 * @property Collection|VoucherDetailExpense[] $voucher_detail_expenses
 *
 * @package App\Models
 */
class VoucherDetail extends Model
{
    use SoftDeletes;

    const STATUS_OUTSTANDING = 1;
	const STATUS_PARTIAL_PAYMENT = 2;
	const STATUS_OVERPAYMENT = 3;
	const STATUS_FULL_PAYMENT = 4;
    const STATUS_OVERDUE = 5;

	protected $table = 'voucher_details';
	public $incrementing = false;

	protected $casts = [
		'amount' => 'float',
		'payment_status' => 'int'
	];

	protected $fillable = [
		'voucher_id',
		'invoice_id',
		'amount',
		'payment_status'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function voucher()
	{
		return $this->belongsTo(Voucher::class);
	}

	public function voucher_detail_expenses()
	{
		return $this->hasMany(VoucherDetailExpense::class);
    }

    public static function getStatusPaid($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_OUTSTANDING :
				return 'Outstanding';
				break;
			case self::STATUS_PARTIAL_PAYMENT :
				return 'Partially Paid';
				break;
			case self::STATUS_OVERPAYMENT :
				return 'Overpayment';
				break;
			case self::STATUS_FULL_PAYMENT :
				return 'Fully Paid';
				break;
			default :
				return 'Overdue';
		}
	}

	public static function getStatusPaidColor($idStatus) {
		switch ($idStatus)
		{
			case self::STATUS_FULL_PAYMENT :
				return 'success';
				break;
			case self::STATUS_OVERPAYMENT :
				return 'warning';
				break;
			case self::STATUS_PARTIAL_PAYMENT :
				return 'warning';
				break;
			case self::STATUS_OUTSTANDING :
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
