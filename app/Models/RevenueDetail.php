<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RevenueDetail
 * 
 * @property int $id
 * @property float $nominal
 * @property int $account_id
 * @property int $revenue_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Account $account
 * @property Revenue $revenue
 *
 * @package App\Models
 */
class RevenueDetail extends Model
{
	use SoftDeletes;
	protected $table = 'revenue_details';

	protected $casts = [
		'nominal' => 'float',
		'account_id' => 'int',
		'revenue_id' => 'int'
	];

	protected $fillable = [
		'nominal',
		'account_id',
		'revenue_id'
	];

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function revenue()
	{
		return $this->belongsTo(Revenue::class);
	}
}
