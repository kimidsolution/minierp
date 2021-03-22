<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FinanceConfigurationDetail
 *
 * @property string $id
 * @property string $account_id
 * @property string $finance_configuration_id
 *
 * @property Account $account
 * @property FinanceConfiguration $finance_configuration
 *
 * @package App\Models
 */
class FinanceConfigurationDetail extends Model
{
	protected $table = 'finance_configuration_details';
	public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

	protected $fillable = [
		'account_id',
		'finance_configuration_id'
    ];

    protected static function boot()
    {
		parent::boot();

        static::creating(function ($model) {
			$model->id = (string) Str::uuid();
        });
    }

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function finance_configuration()
	{
		return $this->belongsTo(FinanceConfiguration::class);
	}
}
