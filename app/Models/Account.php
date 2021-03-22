<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\User;
use Carbon\Carbon;
use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Account
 *
 * @property string $id
 * @property string $company_id
 * @property string|null $parent_account_id
 * @property string $account_name
 * @property string|null $account_text
 * @property string $account_code
 * @property int $level
 * @property string $balance
 * @property int $account_type
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Collection|AccountBalance[] $account_balances
 * @property Collection|Expense[] $expenses
 * @property Collection|InvoiceTax[] $invoice_taxes
 * @property Collection|TransactionDetailTemp[] $transaction_detail_temps
 * @property Collection|TransactionDetail[] $transaction_details
 * @property Collection|VoucherInvoiceExpenseTemp[] $voucher_invoice_expense_temps
 * @property Collection|VoucherInvoiceExpense[] $voucher_invoice_expenses
 * @property Collection|Voucher[] $vouchers
 *
 * @package App\Models
 */
class Account extends Model
{
    use SoftDeletes;

    const ASSETS = 1; // Harta
	const LIABILITIES = 2; // Kewajiban
	const CAPITALS = 3; // Modal
	const INCOME = 4; // Pendapatan
    const COGS = 5; // (Cash of goods sold) Harga Pokok Penjualan
    const EXPENSES = 6; // Beban
    const OTHER_INCOME = 7; // Pendapatan Lain
    const OTHER_EXPENSES = 8; // Beban Lain
    CONST SALES_DISCOUNTS = 'Sales Discounts';

	protected $table = 'accounts';
    public $incrementing = false;
    protected $keyType = 'string';

	protected $casts = [
		'level' => 'int',
		'account_type' => 'int'
	];

	protected $fillable = [
		'company_id',
		'parent_account_id',
		'account_name',
		'account_text',
		'account_code',
		'level',
		'balance',
		'account_type',
		'created_by',
		'updated_by',
		'deleted_by'
    ];

    protected $appends = [
        'naming',
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

	public function account_balances()
	{
		return $this->hasMany(AccountBalance::class);
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class, 'payment_account_id');
	}

	public function invoice_taxes()
	{
		return $this->hasMany(InvoiceTax::class);
	}

	public function transaction_details()
	{
		return $this->hasMany(TransactionDetail::class);
	}

	public function voucher_invoice_expenses()
	{
		return $this->hasMany(VoucherInvoiceExpense::class);
	}

	public function vouchers()
	{
		return $this->hasMany(Voucher::class, 'payment_account_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_account_id', 'id');
    }

    // public function childrens()
    // {
    //     return $this->children()->with('childrens');
    // }

    public function scopeChildless($model)
    {
        $model->has('children', '=', 0);
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    // public function parents()
    // {
    //     return $this->parent()->with('parents');
    // }

    public function scopeParentless($model)
    {
        $model->has('parent', '=', 0);
    }

    public function getNamingAttribute()
    {
        return is_null($this->account_text) || $this->account_text == '' ? $this->account_name : $this->account_text;
    }

    public function getConstants()
    {
        $reflectionClass = new ReflectionClass($this);
        return $reflectionClass->getConstants();
    }
}
