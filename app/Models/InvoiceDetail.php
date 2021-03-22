<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceDetail
 * 
 * @property string $id
 * @property string $product_id
 * @property string $invoice_id
 * @property int $quantity
 * @property float $price
 * 
 * @property Invoice $invoice
 * @property Product $product
 *
 * @package App\Models
 */
class InvoiceDetail extends Model
{
	protected $table = 'invoice_details';
	public $incrementing = false;
	public $timestamps = false;
	protected $keyType = 'string';

	protected $casts = [
		'quantity' => 'int',
		'price' => 'float',
		// 'id' => 'string',
	];

	protected $fillable = [
		'product_id',
		'invoice_id',
		'quantity',
		'price'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	protected static function boot()
    {
		parent::boot();
		
        static::creating(function ($model) {
			$model->id = (string) Str::uuid();
		});
    }
}
