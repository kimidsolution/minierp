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
 * Class InvoiceTax
 * 
 * @property int $id
 * @property float $nominal
 * @property int $account_id
 * @property int $invoice_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Account $account
 * @property Invoice $invoice
 *
 * @package App\Models
 */
class InvoiceTax extends Model
{
	use SoftDeletes;
	protected $table = 'invoice_taxes';

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'amount',
		'account_id',
		'invoice_id'
	];

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	protected static function boot()
    {
		parent::boot();
		
        static::creating(function ($model) {
			$model->id = (string) Str::uuid();
			$model->created_at = Carbon::now();
			$model->updated_at = Carbon::now();
        });
    }
}
