<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionDetail
 *
 * @property string $id
 * @property string $account_id
 * @property string $transaction_id
 * @property float $debit_amount
 * @property float $credit_amount
 *
 * @property Account $account
 * @property Transaction $transaction
 *
 * @package App\Models
 */
class TransactionDetail extends Model
{
	protected $table = 'transaction_details';
	public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

	protected $casts = [
		'debit_amount' => 'float',
		'credit_amount' => 'float'
	];

	protected $fillable = [
		'account_id',
		'transaction_id',
		'debit_amount',
		'credit_amount'
	];

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}

	protected static function boot()
    {
		parent::boot();

        static::creating(function ($model) {
			$model->id = (string) Str::uuid();
        });
    }
}
