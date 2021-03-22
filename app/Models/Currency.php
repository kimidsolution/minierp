<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Currency
 *
 * @property string $id
 * @property string $currency_name
 * @property string $currency_code
 * @property string $iso_code
 * @property string $symbol
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|Company[] $companies
 *
 * @package App\Models
 */
class Currency extends Model
{
	use SoftDeletes;
	protected $table = 'currencies';
	public $incrementing = false;

	protected $fillable = [
		'currency_name',
		'currency_code',
		'iso_code',
		'symbol',
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

	public function companies()
	{
		return $this->belongsToMany(Company::class);
	}
}
