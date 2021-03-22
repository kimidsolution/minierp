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
 * Class Partner
 *
 * @property string $id
 * @property string $company_id
 * @property string $partner_name
 * @property string $email
 * @property string|null $fax
 * @property string|null $phone_number
 * @property string $address
 * @property string|null $tax_id_number
 * @property string $city
 * @property string $country
 * @property string|null $pic_name
 * @property string|null $pic_email
 * @property string|null $pic_phone_number
 * @property bool $is_vendor
 * @property bool $is_client
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Collection|Invoice[] $invoices
 *
 * @package App\Models
 */
class Partner extends Model
{
	use SoftDeletes;
	protected $table = 'partners';
	public $incrementing = false;

	protected $casts = [
		'is_vendor' => 'bool',
		'is_client' => 'bool'
	];

	protected $fillable = [
		'company_id',
		'partner_name',
		'email',
		'fax',
		'phone_number',
		'address',
		'tax_id_number',
		'city',
		'country',
		'pic_name',
		'pic_email',
		'pic_phone_number',
		'is_vendor',
		'is_client',
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

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}
}
