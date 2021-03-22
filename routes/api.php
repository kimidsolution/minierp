<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// datatable
Route::group(['prefix' => 'datatable'], function () {
    // company
    Route::get('companies', function (Request $request) {
        return app('api.datatable.company.service')->handle($request);
    })->name('api.datatable.company.route');
    // user
    Route::get('users', function (Request $request) {
        return app('api.datatable.user.service')->handle($request);
    })->name('api.datatable.user.route');
    // user company
    Route::get('users-company', function (Request $request) {
        return app('api.datatable.user.company.service')->handle($request);
    })->name('api.datatable.user.company.route');
    // account
    Route::get('accounts', function (Request $request) {
        return app('api.datatable.account.service')->handle($request);
    })->name('api.datatable.account.route');
    // currency
    Route::get('currencies', function (Request $request) {
        return app('api.datatable.currencies.service')->handle($request);
    })->name('api.datatable.currencies.route');
    // product
    Route::get('products', function (Request $request) {
        return app('api.datatable.product.service')->handle($request);
    })->name('api.datatable.product.route');
    // partners
    Route::get('partners', function (Request $request) {
        return app('api.datatable.partner.service')->handle($request);
    })->name('api.datatable.partner.route');
    // invoices
    Route::get('invoices', function (Request $request) {
        return app('api.datatable.invoice.service')->handle($request);
    })->name('api.datatable.invoice.route');

    Route::group(['prefix' => 'invoices'], function () {
        Route::get('receivable', function (Request $request) {
            return app('api.datatable.invoice.receivable.service')->handle($request);
        })->name('api.datatable.invoice.receivable.route');
        Route::get('payable', function (Request $request) {
            return app('api.datatable.invoice.payable.service')->handle($request);
        })->name('api.datatable.invoice.payable.route');
    });
    // revenues
    Route::get('revenues', function (Request $request) {
        return app('api.datatable.revenue.service')->handle($request);
    })->name('api.datatable.revenue.route');
    // vouchers
    Route::get('vouchers', function (Request $request) {
        return app('api.datatable.voucher.service')->handle($request);
    })->name('api.datatable.voucher.route');

    Route::group(['prefix' => 'vouchers'], function () {
        Route::get('receivable', function (Request $request) {
            return app('api.datatable.voucher.receivable.service')->handle($request);
        })->name('api.datatable.voucher.receivable.route');
        Route::get('payable', function (Request $request) {
            return app('api.datatable.voucher.payable.service')->handle($request);
        })->name('api.datatable.voucher.payable.route');
    });
     // transactions
    Route::group(['prefix' => 'transactions'], function () {
        Route::get('main', function (Request $request) {
            return app('api.datatable.transactions.main.service')->handle($request);
        })->name('api.datatable.transactions.main.route');
        Route::get('import', function (Request $request) {
            return app('api.datatable.transactions.import.service')->handle($request);
        })->name('api.datatable.transactions.import.route');
        Route::get('details/{id}', function (Request $request) {
            return app('api.datatable.transactions.details.service')->handle($request);
        })->name('api.datatable.transactions.details.route');
    });
    // expenses
    Route::group(['prefix' => 'expense'], function () {
        Route::get('index', function (Request $request) {
            return app('api.datatable.expense.index.service')->handle($request);
        })->name('api.datatable.expense.index.route');
    });
    // configuration
    Route::group(['prefix' => 'configuration'], function () {
        Route::group(['prefix' => 'finance'], function () {
            Route::get('accounts', function (Request $request) {
                return app('api.datatable.configuration.finance.accounts.service')->handle($request);
            })->name('api.datatable.configuration.finance.accounts.route');
            Route::get('details/{id}', function (Request $request) {
                return app('api.datatable.configuration.details.service')->handle($request);
            })->name('api.datatable.configuration.details.route');
        });
    });
});


// finance
Route::group(['prefix' => 'finance'], function () {
    Route::group(['prefix' => 'invoice'], function () {
        Route::post('sales', function (Request $request) {
            return app('api.finance.invoice.create.service')->handle($request);
        })->name('api.finance.invoice.create.route');
        Route::put('sales', function (Request $request) {
            return app('api.finance.invoice.update.service')->handle($request);
        })->name('api.finance.invoice.update.route');
        Route::post('lists-by-partner', function (Request $request) {
            return app('api.finance.list.by.partner.service')->handle($request);
        })->name('api.finance.list.by.partner.route');
        Route::post('lists-by-partner-without-status', function (Request $request) {
            return app('api.finance.list.by.partner.withoutstatus.service')->handle($request);
        })->name('api.finance.list.by.partner.withoutstatus.route');
        Route::group(['prefix' => 'receivable'], function () {
            Route::post('store', function (Request $request) {
                return app('api.finance.invoice.receivable.store.service')->handle($request);
            })->name('api.finance.invoice.receivable.store.route');
            Route::post('update', function (Request $request) {
                return app('api.finance.invoice.receivable.update.service')->handle($request);
            })->name('api.finance.invoice.receivable.update.route');
        });
        Route::group(['prefix' => 'payable'], function () {
            Route::post('store', function (Request $request) {
                return app('api.finance.invoice.payable.store.service')->handle($request);
            })->name('api.finance.invoice.payable.store.route');
            Route::post('update', function (Request $request) {
                return app('api.finance.invoice.payable.update.service')->handle($request);
            })->name('api.finance.invoice.payable.update.route');
        });
    });
    Route::group(['prefix' => 'report'], function () {
        Route::get('general-ledger', function (Request $request) {
            return app('api.finance.report.general.ledger.service')->handle($request);
        })->name('api.finance.report.general.ledger.route');
        Route::get('profit-loss', function (Request $request) {
            return app('api.finance.report.profit.loss.service')->handle($request);
        })->name('api.finance.report.profit.loss.route');
        Route::get('trial-balance', function(Request $request) {
            return app('api.finance.report.trial.balance.service')->handle($request);
        })->name('api.finance.report.trial.balance.route');
        Route::get('journal', function (Request $request) {
            return app('api.finance.report.journal.service')->handle($request);
        })->name('api.finance.report.journal.route');
        Route::get('profit-loss-try', function (Request $request) {
            return app('api.finance.report.profit.loss.try.service')->handle($request);
        })->name('api.finance.report.profit.loss.try.route');
    });
    Route::group(['prefix' => 'voucher'], function () {
        Route::post('sales', function (Request $request) {
            return app('api.finance.voucher.create.service')->handle($request);
        })->name('api.finance.voucher.create.route');
        Route::put('sales', function (Request $request) {
            return app('api.finance.voucher.sales.update.service')->handle($request);
        })->name('api.finance.voucher.sales.update.route');
        Route::post('store', function (Request $request) {
            return app('api.finance.voucher.store.service')->handle($request);
        })->name('api.finance.voucher.store.route');
        Route::post('get-list-invoice-by-id', function (Request $request) {
            return app('api.finance.voucher.get.list.invoice.by.id.service')->handle($request);
        })->name('api.finance.voucher.get.list.invoice.by.id.route');
        Route::post('update', function (Request $request) {
            return app('api.finance.voucher.update.service')->handle($request);
        })->name('api.finance.voucher.update.route');
    });
    Route::group(['prefix' => 'transactions'], function () {
        Route::post('destroy', function(Request $request) {
            return app('api.finance.transaction.destory.service')->handle($request);
        })->name('api.finance.transaction.destroy.route');
        Route::post('checkrefnumber', function(Request $request) {
            return app('api.finance.transaction.checkrefnumber.service')->handle($request);
        })->name('api.finance.transaction.checkrefnumber.route');
        Route::post('store', function(Request $request) {
            return app('api.finance.transaction.store.service')->handle($request);
        })->name('api.finance.transaction.store.route');
        Route::post('posted', function(Request $request) {
            return app('api.finance.transaction.posted.service')->handle($request);
        })->name('api.finance.transaction.posted.route');
        Route::post('load', function(Request $request) {
            return app('api.finance.transaction.load.service')->handle($request);
        })->name('api.finance.transaction.load.route');
    });
    Route::group(['prefix' => 'expense'], function () {
        Route::post('store', function(Request $request) {
            return app('api.finance.expense.store.service')->handle($request);
        })->name('api.finance.expense.store.route');
        Route::post('posted', function(Request $request) {
            return app('api.finance.expense.posted.service')->handle($request);
        })->name('api.finance.expense.posted.route');
        Route::post('destroy', function(Request $request) {
            return app('api.finance.expense.destroy.service')->handle($request);
        })->name('api.finance.expense.destroy.route');
    });
});


// company
Route::group(['prefix' => 'company'], function () {
    Route::post('lists-partner', function (Request $request) {
        return app('api.company.list.partner.service')->handle($request);
    })->name('api.company.list.partner.route');
    Route::post('list-partner-have-invoice-not-yet-paid', function (Request $request) {
        return app('api.company.list.partner.have.invoice.not.yet.paid.service')->handle($request);
    })->name('api.company.list.partner.have.invoice.not.yet.paid.route');
    Route::post('update-status', function(Request $request) {
        return app('api.company.update.status.service')->handle($request);
    })->name('api.company.update.status.route');
    Route::post('detail', function (Request $request) {
        return app('api.company.detail.service')->handle($request);
    })->name('api.company.detail.route');
});

// account
Route::group(['prefix' => 'account'], function () {
    Route::get('lists-account', function (Request $request) {
        return app('api.account.list.account.service')->handle($request);
    })->name('api.account.list.account.route');
    Route::post('store', function (Request $request) {
        return app('api.account.store.service')->handle($request);
    })->name('api.account.store.route');
    Route::post('destroy', function(Request $request) {
        return app('api.account.destory.service')->handle($request);
    })->name('api.account.destroy.route');
    Route::get('lists-parent', function(Request $request) {
        return app('api.account.list.parent.service')->handle($request);
    })->name('api.account.list.parent.route');
    Route::post('check-code', function(Request $request) {
        return app('api.account.check.code.service')->handle($request);
    })->name('api.account.check.code.route');
});

// partner
Route::group(['prefix' => 'partner'], function () {
    Route::post('destroy', function(Request $request) {
        return app('api.partner.destory.service')->handle($request);
    })->name('api.partner.destroy.route');
});

// admin
Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('create', function (Request $request) {
            return app('api.admin.user.store.service')->handle($request);
        })->name('api.admin.user.store.route');
        Route::post('lists-by-company', function (Request $request) {
            return app('api.admin.user.list.by.company.service')->handle($request);
        })->name('api.admin.user.list.by.company.route');
        Route::post('destroy', function(Request $request) {
            return app('api.admin.user.destory.service')->handle($request);
        })->name('api.admin.user.destory.route');
        Route::post('update', function(Request $request) {
            return app('api.admin.user.update.service')->handle($request);
        })->name('api.admin.user.update.route');
    });
    Route::group(['prefix' => 'currencies'], function () {
        Route::post('destroy', function(Request $request) {
            return app('api.admin.currencies.destroy.service')->handle($request);
        })->name('api.admin.currencies.destroy.route');
        Route::post('check-isocode', function(Request $request) {
            return app('api.admin.currencies.check.isocode.service')->handle($request);
        })->name('api.admin.currencies.check.isocode.route');
    });
});

// product
Route::group(['prefix' => 'product'], function () {
    Route::post('update-status', function(Request $request) {
        return app('api.product.update.status.service')->handle($request);
    })->name('api.product.update.status.route');
    Route::post('create', function(Request $request) {
        return app('api.product.store.service')->handle($request);
    })->name('api.product.store.route');
    Route::post('lists-by-company', function (Request $request) {
        return app('api.product.list.by.company.service')->handle($request);
    })->name('api.product.list.by.company.route');
});

// select 2
Route::group(['prefix' => 'select2'], function () {
    Route::post('get-role-by-company-id', function (Request $request) {
        return app('api.select2.get.role.by.company.service')->handle($request);
    })->name('api.select2.get.role.by.company.route');
    Route::post('get-list-account-expenses', function (Request $request) {
        return app('api.select2.get.list.expense.service')->handle($request);
    })->name('api.select2.get.list.expense.route');
    Route::post('get-list-partner-customer-both', function (Request $request) {
        return app('api.select2.get.list.partner.customer.both.service')->handle($request);
    })->name('api.select2.get.list.partner.customer.both.route');
    Route::post('get-list-partner-vendor-both', function (Request $request) {
        return app('api.select2.get.list.partner.vendor.both.service')->handle($request);
    })->name('api.select2.get.list.partner.vendor.both.route');
    Route::post('get-list-account-asset-company', function (Request $request) {
        return app('api.select2.get.list.account.asset.company.service')->handle($request);
    })->name('api.select2.get.list.account.asset.company.route');
    Route::post('get-list-invoice-company', function (Request $request) {
        return app('api.select2.get.list.invoice.company.service')->handle($request);
    })->name('api.select2.get.list.invoice.company.route');
    Route::post('get-list-voucher-company', function (Request $request) {
        return app('api.select2.get.list.voucher.company.service')->handle($request);
    })->name('api.select2.get.list.voucher.company.route');
});

// product category
Route::group(['prefix' => 'product-category'], function () {
    Route::post('lists-by-company', function (Request $request) {
        return app('api.product.category.list.by.company.service')->handle($request);
    })->name('api.product.category.list.by.company.route');
    Route::post('create', function (Request $request) {
        return app('api.product.category.store.service')->handle($request);
    })->name('api.product.category.store.route');
});

// configuration
Route::group(['prefix' => 'configuration'], function () {
    Route::group(['prefix' => 'finance'], function () {
        Route::post('destroy', function(Request $request) {
            return app('api.configuration.finance.destory.service')->handle($request);
        })->name('api.configuration.finance.destroy.route');
        Route::get('getlistavailable', function(Request $request) {
            return app('api.configuration.finance.getlistavailable.service')->handle($request);
        })->name('api.configuration.finance.getlistavailable.route');
        Route::post('store', function(Request $request) {
            return app('api.configuration.finance.store.service')->handle($request);
        })->name('api.configuration.finance.store.route');
        Route::get('load', function(Request $request) {
            return app('api.configuration.finance.load.service')->handle($request);
        })->name('api.configuration.finance.load.route');
        Route::get('check-usage-company', function(Request $request) {
            return app('api.configuration.finance.check.usage.company.service')->handle($request);
        })->name('api.configuration.finance.check.usage.company.route');
    });
});
