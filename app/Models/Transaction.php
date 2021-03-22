<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 *
 * @property string $id
 * @property Carbon $transaction_date
 * @property string|null $model_id
 * @property string|null $model_type
 * @property bool $transaction_type
 * @property int $transaction_status
 * @property string $reference_number
 * @property string $description
 * @property string $company_id
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Collection|Invoice[] $invoices
 * @property Collection|TransactionDetail[] $transaction_details
 * @property Collection|Voucher[] $vouchers
 *
 * @package App\Models
 */
class Transaction extends Model
{
    use SoftDeletes;

    const TYPE_RECEIVABLE = false;
    const TYPE_PAYABLE = true;

    const STATUS_DRAFT = 1;
    const STATUS_POSTED = 2;

    const MODEL_TYPE_INVOICE = 1;
    const MODEL_TYPE_VOUCHER = 2;
    const MODEL_TYPE_OTHERS = 3;

    const MODEL_TYPE_INVOICE_DEC = '\\App\\Models\\Invoice';
    const MODEL_TYPE_VOUCHER_DEC = '\\App\\Models\\Voucher';
    const MODEL_TYPE_EXPENSE_DEC = '\\App\\Models\\Expense';

	protected $table = 'transactions';
    public $incrementing = false;
    protected $keyType = 'string';

	protected $casts = [
		'transaction_type' => 'bool',
		'transaction_status' => 'int'
	];

	protected $dates = [
		'transaction_date'
	];

	protected $fillable = [
		'transaction_date',
		'model_id',
		'model_type',
		'transaction_type',
		'transaction_status',
		'reference_number',
		'description',
        'company_id',
        'created_by',
		'updated_by',
		'deleted_by'
    ];

    protected $appends = [
        'checking_balance_credit',
        'checking_balance_debit'
    ];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function transaction_details()
	{
		return $this->hasMany(TransactionDetail::class);
	}

	public function vouchers()
	{
		return $this->hasMany(Voucher::class);
    }

    public function getCheckingBalanceCreditAttribute()
    {
        $value = $this->transaction_details->sum('credit_amount');
        return $value ? $value : 0;
    }

    public function getCheckingBalanceDebitAttribute()
    {
        $value = $this->transaction_details->sum('debit_amount');
        return $value ? $value : 0;
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

    public static function getListTypeOfTransaction()
    {
        return [
            [
                'id' => Transaction::MODEL_TYPE_INVOICE,
                'name' => 'Invoice'
            ],
            [
                'id' => Transaction::MODEL_TYPE_VOUCHER,
                'name' => 'Voucher'
            ],
            [
                'id' => Transaction::MODEL_TYPE_OTHERS,
                'name' => 'Others'
            ],
        ];
    }
}
