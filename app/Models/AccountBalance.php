<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AccountBalance
 *
 * @property string $id
 * @property string $account_id
 * @property Carbon $balance_date
 * @property float $debit_amount
 * @property float $credit_amount
 * @property string|null $description
 * @property bool $is_closed
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Account $account
 *
 * @package App\Models
 */
class AccountBalance extends Model
{
	use SoftDeletes;
    protected $table = 'account_balances';
	public $incrementing = false;
    protected $keyType = 'string';

	protected $casts = [
		'debit_amount' => 'float',
		'credit_amount' => 'float',
		'is_closed' => 'bool'
	];

	protected $dates = [
		'balance_date'
	];

	protected $fillable = [
		'id',
		'account_id',
		'balance_date',
		'debit_amount',
		'credit_amount',
		'description',
		'is_closed',
		'created_by',
		'updated_by',
		'deleted_by'
    ];

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

	public function account()
	{
		return $this->belongsTo(Account::class);
	}
}
