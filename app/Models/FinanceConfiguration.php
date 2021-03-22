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
 * Class FinanceConfiguration
 *
 * @property string $id
 * @property string $company_id
 * @property int $configuration_code
 * @property string $configuration_description
 * @property bool $configuration_status
 * @property string $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Company $company
 * @property Collection|FinanceConfigurationDetail[] $finance_configuration_details
 *
 * @package App\Models
 */
class FinanceConfiguration extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;

    // Configuration account annual tax per year
    // Specially used in report profit loss
    const CODE_ACCOUNT_ANNUAL_TAX = 1;
    const TEXT_ACCOUNT_ANNUAL_TAX = 'Annual Tax';

    // Configuration account list downpayment
    // Used in page form invoice customer
    const CODE_ACCOUNT_DP_INVOICE_RECEIVABLE = 2;
    const TEXT_ACCOUNT_DP_INVOICE_RECEIVABLE = 'Customer Invoice Down Payment Account';

    // Configuration account list downpayment
    // Used in page form invoice vendor
    const CODE_ACCOUNT_DP_INVOICE_PAYABLE = 3;
    const TEXT_ACCOUNT_DP_INVOICE_PAYABLE = 'Vendor Invoice Down Payment Account';

    // Configuration account list assets
    // Used in page form voucher
    const CODE_ACCOUNT_ASSETS_VOUCHER = 4;
    const TEXT_ACCOUNT_ASSETS_VOUCHER = 'Voucher Assets Account';

    // Configuration account list other expense
    // Used in page form voucher ex: bank admin
    const CODE_ACCOUNT_OTHER_EXPENSE_VOUCHER = 5;
    const TEXT_ACCOUNT_OTHER_EXPENSE_VOUCHER = 'Voucher Other Expense Account';

    // Configuration account cash equivalents
    const CODE_ACCOUNT_CASH_EQUIVALENTS = 6;
    const TEXT_ACCOUNT_CASH_EQUIVALENTS = 'Cash & Equivalents Account';

    // Configuration account sales discount
    // Used in process customer invoice
    const CODE_ACCOUNT_SALES_DISCOUNTS = 7;
    const TEXT_ACCOUNT_SALES_DISCOUNTS = 'Sales Discounts Account';

    // Configuration account good sales
    // Used in process customer invoice
    const CODE_ACCOUNT_GOODS_SALES = 8;
    const TEXT_ACCOUNT_GOODS_SALES = 'Good Sales Account';

    // Configuration account AR sales
    // Used in process customer invoice & voucher (receivable & payable)
    const CODE_ACCOUNT_AR_SALES = 9;
    const TEXT_ACCOUNT_AR_SALES = 'AR Sales Account';

    // Configuration account Vat Out
    // Used in process customer invoice
    const CODE_ACCOUNT_VAT_OUT = 10;
    const TEXT_ACCOUNT_VAT_OUT = 'VAT Out Account';

    // Configuration account Operational Expenses
    // Used in process vendor invoice
    const CODE_ACCOUNT_OPERATIONAL_EXPENSES = 11;
    const TEXT_ACCOUNT_OPERATIONAL_EXPENSES = 'Operational Expense Account';

    // Configuration account Trade Payable
    // Used in process vendor invoice & voucher (payable)
    const CODE_ACCOUNT_TRADE_PAYABLE = 12;
    const TEXT_ACCOUNT_TRADE_PAYABLE = 'Trade Payable Account';

    // Configuration account Vat In
    // Used in process vendor invoice
    const CODE_ACCOUNT_VAT_IN = 13;
    const TEXT_ACCOUNT_VAT_IN = 'VAT In Account';

    // Configuration account Revenue Received In Advanced
    // Used in process voucher (receivable & payable)
    const CODE_ACCOUNT_REVENUE_RECEIVED_IN_ADVANCED = 14;
    const TEXT_ACCOUNT_REVENUE_RECEIVED_IN_ADVANCED = 'Revenue Received In Advanced Account';

    // Configuration account Receivable
    // Used in process voucher (payable)
    const CODE_ACCOUNT_RECEIVABLE = 15;
    const TEXT_ACCOUNT_RECEIVABLE = 'Receivable Account';

    // Configuration pph 23 prepaid with 3 digit only in indonesia country
    // Used in process customer invoice
    const CODE_ACCOUNT_INCOME_TAX23_PREPAID = 111;
    const TEXT_ACCOUNT_INCOME_TAX23_PREPAID = 'Prepaid Income Tax Article 23 Account';

    // Configuration pph 23 payable with 3 digit only in indonesia country
    // Used in process vendor invoice
    const CODE_ACCOUNT_INCOME_TAX23_PAYABLE = 112;
    const TEXT_ACCOUNT_INCOME_TAX23_PAYABLE = 'Income Tax Payable Article 23 Account';

	protected $table = 'finance_configurations';
    public $incrementing = false;
    protected $keyType = 'string';

	protected $casts = [
		'configuration_code' => 'int',
		'configuration_status' => 'bool'
	];

	protected $fillable = [
		'company_id',
		'configuration_code',
		'configuration_description',
		'configuration_status',
		'created_by',
		'updated_by',
		'deleted_by'
    ];

    protected static function boot()
    {
        parent::boot();
        $user_id = app('request')->user_id;
        $user = !is_null($user_id) ? User::find($user_id) : Auth::user();
        $user_name = !is_null($user) ? $user->name : 'System';

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

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function finance_configuration_details()
	{
		return $this->hasMany(FinanceConfigurationDetail::class);
	}
}
