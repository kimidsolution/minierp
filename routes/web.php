<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/starter', function () {
    return view('welcome');
})->name('starter');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'admin', 'as'=>'admin.', 'middleware' => ['auth']], function() {
    Route::get('companies/users/{id}', 'Admin\UserController@usercompany')->name('users-company');
    Route::resource('users', 'Admin\UserController');
    Route::resource('companies', 'Admin\CompanyController')->middleware(['can:isAdmin']);
    Route::resource('currencies', 'Admin\CurrenciesController')->middleware(['can:isAdmin']);
});

Route::group(['prefix'=>'master', 'as'=>'master.', 'middleware' => ['auth']], function() {
    Route::resource('product', 'Master\ProductController');
    Route::resource('partner', 'Master\PartnerController');
});


Route::group(['prefix'=>'finance', 'as'=>'finance.', 'middleware' => ['auth']], function() {
    Route::resource('revenues', 'Finance\RevenuesController');
    Route::resource('expenses', 'Finance\ExpenseController');
    Route::resource('accounts', 'Finance\AccountController');
    Route::group(['prefix' => 'transactions', 'as' => 'transactions.', 'middleware' => ['auth']], function() {
        Route::group(['prefix' => 'receivable', 'as' => 'receivable.', 'middleware' => ['auth']], function() {
            Route::get('/', 'Finance\TransactionController@indexReceivable')->name('index');
            Route::get('/create', 'Finance\TransactionController@createReceivable')->name('create');
            Route::get('/{id}/edit', 'Finance\TransactionController@editReceivable')->name('edit');
        });
        Route::group(['prefix' => 'payable', 'as' => 'payable.', 'middleware' => ['auth']], function() {
            Route::get('/', 'Finance\TransactionController@indexPayable')->name('index');
            Route::get('/create', 'Finance\TransactionController@createPayable')->name('create');
            Route::get('/{id}/edit', 'Finance\TransactionController@editPayable')->name('edit');
        });
        Route::get('/import', 'Finance\TransactionController@importIndexTransaction')->name('import.index');
        Route::get('/import/view-upload', 'Finance\TransactionController@importUploadViewTransaction')->name('import.upload.view');
        Route::post('/import/upload', 'Finance\TransactionController@importUploadPostTransaction')->name('import.upload.post');
    });

    Route::group(['prefix'=>'report', 'as'=>'report.', 'middleware' => ['auth']], function() {
        Route::get('/general-ledger', 'Finance\ReportController@indexGeneralLedger')->name('general-ledger');
        Route::get('/profit-loss', 'Finance\ReportController@indexProfitLoss')->name('profit-loss');
        Route::get('/trial-balance', 'Finance\ReportController@indexTrialBalance')->name('trial-balance');
        Route::get('/journal', 'Finance\ReportController@indexJournal')->name('journal');
        Route::get('/journal/transaction/{id}', 'Finance\ReportController@indexJournalTransaction')->name('journal-transaction');
    });

    Route::group(['prefix'=>'invoices', 'as'=>'invoices.', 'middleware' => ['auth']], function() {
        Route::get('receivable/partner/{id}', 'Finance\InvoiceReceivableController@invoicespartner')->name('invoices-receivable-partner');
        Route::get('payable/partner/{id}', 'Finance\InvoicePayableController@invoicespartner')->name('invoices-payable-partner');
        Route::resource('receivable', 'Finance\InvoiceReceivableController');
        Route::resource('payable', 'Finance\InvoicePayableController');
        Route::get('receivable/posted/{id}', 'Finance\InvoiceReceivableController@posted')->name('receivable.posted');
        Route::get('payable/posted/{id}', 'Finance\InvoicePayableController@posted')->name('payable.posted');
    });

    Route::resource('vouchers', 'Finance\VoucherController');

    Route::get('invoices/posted/{id}', 'Finance\InvoiceController@posted')->name('invoices.posted');
    Route::get('vouchers/posted/{id}', 'Finance\VoucherController@posted')->name('vouchers.posted');
    Route::get('invoices/delete/{id}', 'Finance\InvoiceController@delete')->name('invoices.delete');
    Route::get('vouchers/delete/{id}', 'Finance\VoucherController@delete')->name('vouchers.delete');
});

Route::group(['prefix'=>'configuration', 'as'=>'configuration.', 'middleware' => ['auth']], function() {
    Route::group(['prefix'=>'finance', 'as'=>'finance.', 'middleware' => ['auth']], function() {
        Route::group(['prefix' => 'accounts', 'as' => 'accounts.', 'middleware' => ['auth']], function() {
            Route::get('/', 'Configuration\FinanceConfigurationController@indexAccounts')->name('index');
            Route::get('/create', 'Configuration\FinanceConfigurationController@createAccounts')->name('create');
            Route::get('/{id}/edit', 'Configuration\FinanceConfigurationController@editAccounts')->name('edit');
        });
    });
});

Route::get('profile', 'ProfileController@index')->middleware(['auth'])->name('profile');
Route::post('profile', 'ProfileController@store')->middleware(['auth'])->name('profile.store');
Route::get('reset-password', 'Admin\UserController@resetpassword')->name('user.reset.password');
Route::post('reset-password', 'Admin\UserController@updatepassword')->name('user.update.password');
Route::get('admin-reset-password/{id}', 'Admin\UserController@adminresetpassworduser')->name('admin.reset.user.password');
Route::post('admin-reset-password', 'Admin\UserController@adminupdatepassworduser')->name('admin.update.user.password');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
