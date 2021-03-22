<?php

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Company;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Partner;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\VoucherDetail;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ('local' == config('app.env')) {
            $warung = Company::where('company_name', 'CV Warung Sejahtera')->first();
            $indomie = Product::where('company_id', $warung->id)->where('product_name', 'Indomie')->first();
            $revenueAccount = Account::where('company_id', $warung->id)->where('account_name', 'Revenue')->first();
            $partner_default = Partner::where('company_id', $warung->id)->where('partner_name', 'General Customer')->first();

            $previousMonth = 0;

            // for each day of the year
            for ($i=0; $i<10; $i++) {

                // get transaction date
                $transactionDate = Carbon::createFromDate(2020,1,1)->addDays($i);

                // randomized number of transactions for the day
                $numTransactions = rand(0,20);

                // for each transaction for the day
                for ($j=0; $j<$numTransactions; $j++) {

                    // randomized number of items purchased
                    $numItems = rand(1,20);

                    // if previousMonth is not same as current month, set transactionCount to 0, else add 1
                    $transactionCount = ($previousMonth != $transactionDate->month) ? 0 : $transactionCount + 1;

                    // set previousMonth to current month
                    $previousMonth = $transactionDate->month;

                    // set voucher number
                    $voucherNumber = 'VOU/'.($transactionCount+1).'/'.$transactionDate->month.'/2020';

                    // create voucher
                    $voucher = Voucher::create([
                        'id'            => Str::uuid(),
                        'company_id'    => $warung->id,
                        'partner_id'    => $partner_default->id,
                        'voucher_date'  => $transactionDate,
                        'voucher_type'  => Voucher::TYPE_RECEIVABLE,
                        'voucher_number'    => $voucherNumber,
                        'is_posted'         => Voucher::POSTED_YES,
                        'payment_account_id'    => $revenueAccount->id,
                        'created_by'    => 'System',
                        'updated_by'    => 'System'
                    ]);

                    // Make Voucher's Transaction
                    $transaction = Transaction::create([
                        'company_id' => $warung->id,
                        'transaction_date'  => $transactionDate,
                        'model_id'          => $voucher->id,
                        'model_type'        => '\App\Models\Voucher',
                        'transaction_type'  => Transaction::TYPE_RECEIVABLE,
                        'transaction_status'  => Transaction::STATUS_POSTED,
                        'reference_number'  => $voucherNumber,
                        'description'       => 'Penjualan Indomie variasi',
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                        'created_by'        => 'System',
                        'updated_by'        => 'System'
                    ]);

                    // amount with credit balance
                    TransactionDetail::create([
                        'transaction_id'    => $transaction->id,
                        'account_id'        => $revenueAccount->id,
                        'debit_amount'      => 0,
                        'credit_amount'     => $numItems * $indomie['price']
                    ]);

                    // create voucher line
                    VoucherDetail::create([
                        'id' => Str::uuid(),
                        'voucher_id'        => $voucher->id,
                        'amount'            => $numItems * $indomie['price'],
                        'payment_status'    => VoucherDetail::STATUS_FULL_PAYMENT
                    ]);
                }
            }
        }
    }
}
