<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Voucher;
use App\Models\VoucherDetail;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ('local' == config('app.env')) {
            $company = \App\Models\Company::where('company_name', 'PT Kapzet Teknologi Informasi')->first();
            $partner = \App\Models\Partner::where('partner_name', 'PT Citra Persada Infrastruktur')
                ->where('company_id', $company->id)
                ->first();

            for ($i=0; $i<12; $i++) {
                // Make Invoice

                $downpaymentAccount = Account::where('company_id', $company->id)->where('account_name', 'Cash')->first();
                $invoiceNumber = 'INV/'.($i+1).'/'.($i+1).'/2020';
                $invoice = Invoice::create([
                    'type'                          => Invoice::TYPE_RECEIVABLE,
                    'payment_status'                => Invoice::STATUS_OUTSTANDING,
                    'invoice_date'                  => Carbon::createFromDate(2020, $i+1, 1),
                    'due_date'                      => Carbon::createFromDate(2020, $i+1, 1)->addDays(30),
                    'is_posted'                     => Invoice::POSTED_YES,
                    'sent_to_partner'               => Invoice::SEND_PARTNER_YES,
                    'invoice_number'                => $invoiceNumber,
                    'discount'                      => 0,
                    'down_payment'                  => 0,
                    'total_amount'                  => 15000000,
                    'note'                          => '',
                    'purchase_order'                => '',
                    'company_id'                    => $company->id,
                    'partner_id'                    => $partner->id,
                    'downpayment_account_id'        => $downpaymentAccount->id,
                    'created_by'                    => 'System',
                    'created_at'                    => now(),
                    'updated_by'                    => 'System',
                    'updated_at'                    => now()
                ]);

                $product = \App\Models\Product::where('company_id', $company->id)
                    ->where('product_name', 'Jasa Konsultan CTO')
                    ->first();
                \App\Models\InvoiceDetail::create([
                    'invoice_id'    => $invoice->id,
                    'product_id'    => $product->id,
                    'quantity'      => 1,
                    'price'         => 15000000,
                ]);

                // Make Invoice's Transaction
                $transaction = Transaction::create([
                    'company_id'        => $company->id,
                    'transaction_date'  => Carbon::createFromDate(2020, $i+1, 1),
                    'model_id'          => $invoice->id,
                    'model_type'        => '\App\Models\Invoice',
                    'transaction_type'  => Transaction::TYPE_RECEIVABLE,
                    'transaction_status'  => Transaction::STATUS_POSTED,
                    'reference_number'  => $invoiceNumber,
                    'description'       => '',
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'created_by'        => 'System',
                    'updated_by'        => 'System'
                ]);

                $arAccount = Account::where('company_id', $company->id)->where('account_name', 'AR Sales')->first();
                $transDebit = TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'account_id'        => $arAccount->id,
                    'debit_amount'      => 15000000,
                    'credit_amount'     => 0
                ]);

                $salesAccount = Account::where('company_id', $company->id)->where('account_name', 'Revenue')->first();
                $transCredit = TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'account_id'        => $salesAccount->id,
                    'debit_amount'      => 0,
                    'credit_amount'     => 15000000
                ]);

                // Make Voucher

                $cashAccount = Account::where('company_id', $company->id)->where('account_name', 'Cash')->first();
                $voucherNumber = 'VOU/'.($i+1).'/'.($i+1).'/2020';
                $voucher = Voucher::create([
                    'company_id'            => $company->id,
                    'voucher_date'          => Carbon::createFromDate(2020, $i+1, 1)->addDays(14),
                    'voucher_type'          => Voucher::TYPE_RECEIVABLE,
                    'voucher_number'        => $voucherNumber,
                    'is_posted'             => Voucher::POSTED_YES,
                    'payment_account_id'    => $cashAccount->id,
                    'partner_id'            => $partner->id,
                    'created_by'            => 'System',
                    'updated_by'            => 'System'
                ]);

                // Make Voucher's Transaction

                $transaction = Transaction::create([
                    'company_id'        => $company->id,
                    'transaction_date'  => Carbon::createFromDate(2020, $i+1, 1)->addDays(14),
                    'model_id'          => $voucher->id,
                    'model_type'        => '\App\Models\Voucher',
                    'transaction_type'  => Transaction::TYPE_RECEIVABLE,
                    'transaction_status'  => Transaction::STATUS_POSTED,
                    'reference_number'  => $voucherNumber,
                    'description'       => '',
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'created_by'        => 'System',
                    'updated_by'        => 'System'
                ]);

                $transDebit = TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'account_id'        => $cashAccount->id,
                    'debit_amount'      => 15000000,
                    'credit_amount'     => 0
                ]);

                $transCredit = TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'account_id'        => $arAccount->id,
                    'debit_amount'      => 0,
                    'credit_amount'     => 15000000
                ]);

                // Make Voucher's Invoice

                VoucherDetail::create([
                    'voucher_id'        => $voucher->id,
                    'invoice_id'        => $invoice->id,
                    'amount'            => 15000000,
                    'payment_status'    => VoucherDetail::STATUS_FULL_PAYMENT
                ]);

                // Update invoice's status

                $invoice->payment_status = Invoice::STATUS_FULL_PAYMENT;
                $invoice->save();
            }
        }
    }
}
