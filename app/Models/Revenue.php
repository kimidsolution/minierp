<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Revenue
 * 
 * @property int $id
 * @property string $uuid
 * @property Carbon $date
 * @property string $number
 * @property float $amount
 * @property string $is_posted
 * @property string|null $description
 * @property int $company_id
 * @property int $paid_to
 * @property int $created_by
 * @property int|null $posted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Company $company
 * @property User $user
 * @property Account $account
 * @property Collection|RevenueDetail[] $revenue_details
 *
 * @package App\Models
 */
class Revenue extends Model
{
	use SoftDeletes;
	protected $table = 'revenues';

	protected $casts = [
		'amount' => 'float',
		'company_id' => 'int',
		'paid_to' => 'int',
		'created_by' => 'int',
		'posted_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'uuid',
		'date',
		'number',
		'amount',
		'is_posted',
		'description',
		'company_id',
		'paid_to',
		'created_by',
		'posted_by'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'posted_by');
	}

	public function account()
	{
		return $this->belongsTo(Account::class, 'paid_to');
	}

	public function revenue_details()
	{
		return $this->hasMany(RevenueDetail::class);
	}
}
