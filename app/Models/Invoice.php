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
 * Class Invoice
 *
 * @property string $id
 * @property string $company_id
 * @property string $partner_id
 * @property int $type
 * @property int $payment_status
 * @property Carbon $invoice_date
 * @property Carbon $due_date
 * @property int $is_posted
 * @property int $sent_to_partner
 * @property string $invoice_number
 * @property float $discount
 * @property float $down_payment
 * @property float $total_amount
 * @property string|null $note
 * @property string|null $purchase_order
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Partner $partner
 * @property Collection|InvoiceDetail[] $invoice_details
 * @property Collection|InvoiceTax[] $invoice_taxes
 * @property Collection|VoucherDetail[] $voucher_details
 *
 * @package App\Models
 */
class Invoice extends Model
{
    use SoftDeletes;

    const TYPE_RECEIVABLE = 1;
	const TYPE_PAYABLE = 2;

	const STATUS_OUTSTANDING = 1; // belum dibayar sama sekali tetapi belum lewat due date
	const STATUS_PARTIAL_PAYMENT = 2; // udh bayar tapi blm lunas, dan belum lewat due date
	const STATUS_OVERPAYMENT = 3; // lebih bayar
	const STATUS_FULL_PAYMENT = 4; // udah bayar lunas
	const STATUS_OVERDUE = 5; // udah bayar atau belum, tapi sudah lewat due date

	const POSTED_YES = 1;
	const POSTED_NO = 2;

	const SEND_PARTNER_YES = 1;
    const SEND_PARTNER_NO = 2;

	protected $table = 'invoices';
	public $incrementing = false;
	protected $keyType = 'string';

	protected $casts = [
		'type' => 'int',
		'payment_status' => 'int',
		'is_posted' => 'int',
		'sent_to_partner' => 'int',
		'discount' => 'float',
		'down_payment' => 'float',
		'total_amount' => 'float',
		// 'id' => 'string'
	];

	protected $dates = [
		'invoice_date',
		'due_date'
	];

	protected $fillable = [
		'company_id',
		'partner_id',
		'downpayment_account_id',
		'type',
		'payment_status',
		'invoice_date',
		'due_date',
		'is_posted',
		'sent_to_partner',
		'invoice_number',
		'discount',
		'down_payment',
		'total_amount',
		'note',
		'purchase_order',
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

	public function invoice_details()
	{
		return $this->hasMany(InvoiceDetail::class);
	}

	public function invoice_taxes()
	{
		return $this->hasMany(InvoiceTax::class);
	}

	public function voucher_details()
	{
		return $this->hasMany(VoucherDetail::class);
    }

    public function vouchers()
	{
		return $this->belongsToMany(Voucher::class, 'voucher_details')
					->withPivot('id', 'transaction_id', 'amount', 'payment_status', 'deleted_at')
					->withTimestamps();
	}

	public static function getTypeOfInvoice($idType) {
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

	public static function getTypeColorInvoice($idStatus) {
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

	public static function getStatusOfInvoice($idType) {
		switch ($idType)
		{
			case self::STATUS_OUTSTANDING :
				return 'Outstanding';
				break;
			case self::STATUS_PARTIAL_PAYMENT :
				return 'Partial Payment';
				break;
			case self::STATUS_OVERPAYMENT :
				return 'Overpayment';
				break;
			case self::STATUS_FULL_PAYMENT :
				return 'Paid';
				break;
			default:
				return 'Overdue';
		}
	}

	public static function getStatusColorInvoice($idStatus) {
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
				return 'warning';
				break;
			default:
				return 'danger';
		}
	}

	public static function getPostedOfInvoice($idType) {
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

	public static function getPostedColorInvoice($idStatus) {
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
