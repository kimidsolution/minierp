<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // response macro
        Response::macro('api', function ($status, $meta = [], $data = [], $message = '', $httpCode = 200) {
            return response()->json([
                'status' => $status,
                'meta' => $meta,
                'data' => $data,
                'message' => $message
            ], $httpCode);
        });



        // helper
        $this->app->bind(
            'array.helper',
            \App\Helpers\ArrayHelper::class
        );

        $this->app->bind(
            'string.helper',
            \App\Helpers\StringHelper::class
        );

        $this->app->bind(
            'data.helper',
            \App\Helpers\DataHelper::class
        );

        $this->app->bind(
            'invoice.helper',
            \App\Helpers\InvoiceHelper::class
        );



        // service
        $this->app->bind(
            'api.datatable.company.service',
            \App\Services\Api\Datatable\Company\CompanyService::class
        );

        $this->app->bind(
            'api.datatable.user.service',
            \App\Services\Api\Datatable\User\UserService::class
        );

        $this->app->bind(
            'api.datatable.account.service',
            \App\Services\Api\Datatable\Account\AccountService::class
        );

        $this->app->bind(
            'api.datatable.currencies.service',
            \App\Services\Api\Datatable\Currencies\CurrenciesService::class
        );

        $this->app->bind(
            'api.datatable.product.service',
            \App\Services\Api\Datatable\Product\ProductService::class
        );

        $this->app->bind(
            'api.datatable.partner.service',
            \App\Services\Api\Datatable\Partner\PartnerService::class
        );

        $this->app->bind(
            'api.datatable.invoice.service',
            \App\Services\Api\Datatable\Invoice\InvoiceService::class
        );

        $this->app->bind(
            'api.datatable.invoice.receivable.service',
            \App\Services\Api\Datatable\Invoice\Receivable\InvoiceReceivableService::class
        );

        $this->app->bind(
            'api.datatable.invoice.payable.service',
            \App\Services\Api\Datatable\Invoice\Payable\InvoicePayableService::class
        );

        $this->app->bind(
            'api.datatable.revenue.service',
            \App\Services\Api\Datatable\Revenue\RevenueService::class
        );

        $this->app->bind(
            'api.datatable.transactions.main.service',
            \App\Services\Api\Datatable\Transaction\TransactionService::class
        );

        $this->app->bind(
            'api.datatable.transactions.import.service',
            \App\Services\Api\Datatable\Transaction\TransactionImportService::class
        );

        $this->app->bind(
            'api.datatable.transactions.details.service',
            \App\Services\Api\Datatable\Transaction\TransactionDetailsService::class
        );

        $this->app->bind(
            'api.datatable.expense.index.service',
            \App\Services\Api\Datatable\Expense\ExpenseService::class
        );

        $this->app->bind(
            'api.datatable.configuration.finance.accounts.service',
            \App\Services\Api\Datatable\Configuration\FinanceConfigurationAccountService::class
        );

        $this->app->bind(
            'api.datatable.configuration.details.service',
            \App\Services\Api\Datatable\Configuration\FinanceConfigurationDetailService::class
        );

        $this->app->bind(
            'api.finance.invoice.create.service',
            \App\Services\Api\Finance\Invoice\Create\CreateService::class
        );

        $this->app->bind(
            'api.finance.report.general.ledger.service',
            \App\Services\Api\Finance\Report\GeneralLedger\GeneralLedgerService::class
        );

        $this->app->bind(
            'api.finance.report.profit.loss.service',
            \App\Services\Api\Finance\Report\ProfitLoss\ProfitLossService::class
        );

        $this->app->bind(
            'api.finance.report.journal.service',
            \App\Services\Api\Finance\Report\Journal\JournalService::class
        );

        $this->app->bind(
            'api.company.list.partner.service',
            \App\Services\Api\Company\ListsPartner\ListsPartnerService::class
        );

        $this->app->bind(
            'api.company.detail.service',
            \App\Services\Api\Company\Detail\GetDetailCompanyService::class
        );

        $this->app->bind(
            'api.company.list.partner.have.invoice.not.yet.paid.service',
            \App\Services\Api\Company\ListsPartnerHaveInvoiceNotYetPaid\ListsPartnerHaveInvoiceNotYetPaidService::class
        );

        $this->app->bind(
            'api.company.update.status.service',
            \App\Services\Api\Company\UpdateStatus\CompanyUpdateStatusService::class
        );

        $this->app->bind(
            'api.finance.list.by.partner.service',
            \App\Services\Api\Finance\Invoice\ListByPartner\ListByPartnerService::class
        );

        $this->app->bind(
            'api.finance.list.by.partner.withoutstatus.service',
            \App\Services\Api\Finance\Invoice\ListByPartnerWithoutStatus\ListByPartnerService::class
        );

        $this->app->bind(
            'api.finance.voucher.create.service',
            \App\Services\Api\Finance\Voucher\Create\CreateService::class
        );

        $this->app->bind(
            'api.datatable.voucher.service',
            \App\Services\Api\Datatable\Voucher\VoucherService::class
        );

        $this->app->bind(
            'api.datatable.voucher.receivable.service',
            \App\Services\Api\Datatable\Voucher\Receivable\VoucherReceivableService::class
        );

        $this->app->bind(
            'api.datatable.voucher.payable.service',
            \App\Services\Api\Datatable\Voucher\Payable\VoucherPayableService::class
        );

        $this->app->bind(
            'api.finance.invoice.update.service',
            \App\Services\Api\Finance\Invoice\Update\UpdateService::class
        );

        $this->app->bind(
            'api.finance.voucher.sales.update.service',
            \App\Services\Api\Finance\Voucher\UpdateSales\UpdateSalesService::class
        );

        $this->app->bind(
            'api.finance.expense.store.service',
            \App\Services\Api\Finance\Expense\Store\StoreService::class
        );

        $this->app->bind(
            'finance.revenue.store.service',
            \App\Services\Finance\Revenue\Store\StoreService::class
        );

        $this->app->bind(
            'api.finance.report.trial.balance.service',
            \App\Services\Api\Finance\Report\TrialBalance\TrialBalanceService::class
        );

        $this->app->bind(
            'api.account.list.account.service',
            \App\Services\Api\Account\Lists\ListsAccount\ListsAccountService::class
        );

        $this->app->bind(
            'api.account.list.parent.service',
            \App\Services\Api\Account\Lists\ListsParent\ListParentService::class
        );

        $this->app->bind(
            'api.account.store.service',
            \App\Services\Api\Account\StoreAccount\StoreAccountService::class
        );

        $this->app->bind(
            'api.account.check.code.service',
            \App\Services\Api\Account\Check\CheckCode\CheckCodeService::class
        );

        // data
        $this->app->bind(
            'api.datatable.user.company.service',
            \App\Services\Api\Datatable\User\UserCompanyService::class
        );

        $this->app->bind(
            'api.partner.destory.service',
            \App\Services\Api\Partner\Flagging\Destroy\PartnerDestroyService::class
        );

        $this->app->bind(
            'api.account.destory.service',
            \App\Services\Api\Account\Flagging\Destroy\AccountDestroyService::class
        );

        // admin
        $this->app->bind(
            'api.admin.user.store.service',
            \App\Services\Api\Admin\User\Create\CreateService::class
        );

        $this->app->bind(
            'api.admin.user.list.by.company.service',
            \App\Services\Api\Admin\User\ListByCompany\ListUserService::class
        );

        $this->app->bind(
            'api.admin.user.destory.service',
            \App\Services\Api\Admin\User\Destroy\DestroyService::class
        );

        $this->app->bind(
            'api.admin.user.update.service',
            \App\Services\Api\Admin\User\Update\UpdateService::class
        );

        // select2
        $this->app->bind(
            'api.select2.get.role.by.company.service',
            \App\Services\Api\Select2\GetRoleByCompany\GetRoleByCompanyService::class
        );

        // master
        $this->app->bind(
            'api.product.update.status.service',
            \App\Services\Api\Product\UpdateStatus\ProductUpdateStatusService::class
        );

        $this->app->bind(
            'api.product.store.service',
            \App\Services\Api\Product\Create\CreateService::class
        );

        $this->app->bind(
            'api.product.list.by.company.service',
            \App\Services\Api\Product\ListsByCompany\ListService::class
        );

        $this->app->bind(
            'api.product.category.list.by.company.service',
            \App\Services\Api\ProductCategory\ListsByCompany\ListService::class
        );

        $this->app->bind(
            'api.product.category.store.service',
            \App\Services\Api\ProductCategory\Create\CreateService::class
        );

        $this->app->bind(
            'api.finance.transaction.destory.service',
            \App\Services\Api\Finance\Transaction\Flagging\Destroy\TransactionDestroyService::class
        );

        $this->app->bind(
            'api.finance.transaction.posted.service',
            \App\Services\Api\Finance\Transaction\Flagging\Posted\TransactionPostedService::class
        );

        $this->app->bind(
            'api.finance.transaction.checkrefnumber.service',
            \App\Services\Api\Finance\Transaction\Check\CheckRefNumber\CheckRefNumberService::class
        );

        $this->app->bind(
            'api.finance.transaction.store.service',
            \App\Services\Api\Finance\Transaction\Store\StoreTransactionService::class
        );

        $this->app->bind(
            'api.finance.invoice.receivable.store.service',
            \App\Services\Api\Finance\Invoice\Receivable\Store\StoreService::class
        );

        $this->app->bind(
            'api.admin.currencies.destroy.service',
            \App\Services\Api\Admin\Currencies\Destroy\DestroyService::class
        );

        $this->app->bind(
            'api.finance.invoice.payable.store.service',
            \App\Services\Api\Finance\Invoice\Payable\Store\StoreService::class
        );

        $this->app->bind(
            'api.admin.currencies.check.isocode.service',
            \App\Services\Api\Admin\Currencies\Check\CheckIsoCode\CheckIsoCodeService::class
        );

        $this->app->bind(
            'api.finance.voucher.store.service',
            \App\Services\Api\Finance\Voucher\Store\StoreService::class
        );

        $this->app->bind(
            'api.select2.get.list.expense.service',
            \App\Services\Api\Select2\GetListExpense\GetListExpenseService::class
        );

        $this->app->bind(
            'api.select2.get.list.partner.customer.both.service',
            \App\Services\Api\Select2\GetPartnerCustomerBoth\GetPartnerCustomerBothService::class
        );

        $this->app->bind(
            'api.select2.get.list.account.asset.company.service',
            \App\Services\Api\Select2\GetListAccountAssetCompany\GetListAccountAssetCompanyService::class
        );

        $this->app->bind(
            'api.select2.get.list.partner.vendor.both.service',
            \App\Services\Api\Select2\GetPartnerVendorBoth\GetPartnerVendorBothService::class
        );

        $this->app->bind(
            'api.select2.get.list.invoice.company.service',
            \App\Services\Api\Select2\GetListInvoiceCompany\GetListInvoiceService::class
        );

        $this->app->bind(
            'api.select2.get.list.voucher.company.service',
            \App\Services\Api\Select2\GetListVoucherCompany\GetListVoucherService::class
        );

        $this->app->bind(
            'api.finance.invoice.receivable.update.service',
            \App\Services\Api\Finance\Invoice\Receivable\Update\UpdateService::class
        );

        $this->app->bind(
            'api.finance.invoice.payable.update.service',
            \App\Services\Api\Finance\Invoice\Payable\Update\UpdateService::class
        );

        $this->app->bind(
            'api.finance.transaction.load.service',
            \App\Services\Api\Finance\Transaction\Load\LoadService::class
        );

        $this->app->bind(
            'api.finance.voucher.get.list.invoice.by.id.service',
            \App\Services\Api\Finance\Voucher\GetListInvoiceById\GetListInvoiceByIdService::class
        );

        $this->app->bind(
            'api.finance.voucher.update.service',
            \App\Services\Api\Finance\Voucher\Update\UpdateService::class
        );

        $this->app->bind(
            'api.finance.expense.posted.service',
            \App\Services\Api\Finance\Expense\Flagging\Posted\ExpensePostedService::class
        );

        $this->app->bind(
            'api.finance.expense.destroy.service',
            \App\Services\Api\Finance\Expense\Flagging\Destroy\ExpenseDestroyService::class
        );

        $this->app->bind(
            'api.finance.report.profit.loss.try.service',
            \App\Services\Api\Finance\Report\ProfitLossTry\ProfitLossService::class
        );

        $this->app->bind(
            'api.configuration.finance.destory.service',
            \App\Services\Api\Configuration\Finance\Flagging\Destroy\DestroyService::class
        );

        $this->app->bind(
            'api.configuration.finance.getlistavailable.service',
            \App\Services\Api\Configuration\Finance\ListAvailable\ListAvailableService::class
        );

        $this->app->bind(
            'api.configuration.finance.store.service',
            \App\Services\Api\Configuration\Finance\Store\StoreService::class
        );

        $this->app->bind(
            'api.configuration.finance.load.service',
            \App\Services\Api\Configuration\Finance\Load\LoadService::class
        );

        $this->app->bind(
            'api.configuration.finance.check.usage.company.service',
            \App\Services\Api\Configuration\Finance\Check\Usage\CheckUsageCompanyService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Schema::defaultStringLength(191);
    }
}
