<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VoucherDetailExpense
 *
 * @property string $id
 * @property string $account_id
 * @property string $voucher_detail_id
 * @property float $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Account $account
 * @property VoucherDetail $voucher_detail
 *
 * @package App\Models
 */
class VoucherDetailExpense extends Model
{
	use SoftDeletes;
	protected $table = 'voucher_detail_expenses';
	public $incrementing = false;

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'account_id',
		'voucher_detail_id',
		'amount'
	];

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function voucher_detail()
	{
		return $this->belongsTo(VoucherDetail::class);
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
