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
 * Class Expense
 *
 * @property string $id
 * @property string $company_id
 * @property string $payment_account_id
 * @property Carbon $expense_date
 * @property string $reference_number
 * @property float $amount
 * @property bool $is_posted
 * @property string|null $description
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Account $account
 *
 * @package App\Models
 */
class Expense extends Model
{
    use SoftDeletes;

    const STATUS_DRAFT = false;
    const STATUS_POSTED = true;

	protected $table = 'expenses';
	public $incrementing = false;

	protected $casts = [
		'amount' => 'float',
		'is_posted' => 'bool'
	];

	protected $dates = [
		'expense_date'
	];

	protected $fillable = [
		'company_id',
		'payment_account_id',
		'expense_date',
		'reference_number',
		'amount',
		'is_posted',
		'description',
		'created_by',
		'updated_by',
		'deleted_by'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function account()
	{
		return $this->belongsTo(Account::class, 'payment_account_id');
    }

    protected static function boot()
    {
        parent::boot();
        $user_id = app('request')->user_id;
        $user = !is_null($user_id) ? User::find($user_id) : Auth::user();
        $user_name = !is_null($user) ? $user->name : '';

        static::creating(function($model) use ($user_name) {
            $model->id = (string) Str::uuid();
            $model->created_at = now();
            $model->updated_at = now();
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
}
