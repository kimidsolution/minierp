<?php

namespace App\Helpers;

use DB;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Voucher;
use App\Models\VoucherDetail;

class InvoiceHelper
{
    /**
     * get nominal remaining payment invoice
     *
     * @param integer $invoiceId
     * @param integer $finalAmount
     * @return integer 
     */
    public function getNominalRemainingPaymentInvoice($invoiceId, $finalAmount)
    {
        $totalVoucherDetail = (int) VoucherDetail::where('invoice_id', $invoiceId)->sum('amount');
        return (int) $finalAmount - $totalVoucherDetail;
    }

    public function getNominalVoucherInvoice($voucherId)
    {
        $voucher = Voucher::find($voucherId);

        if (is_null($voucher))
            return 0;
        
        $voucherDetails = VoucherDetail::with('voucher_detail_expenses')->where('voucher_id', $voucherId)->get();
        if ($voucherDetails->count() < 1)
            return 0;
        
        $sum_detail_voucher_invoice_expense = 0;
        foreach ($voucherDetails as $keys => $values) {
            if ($values->voucher_detail_expenses->count() > 0) {
                foreach ($values->voucher_detail_expenses as $key => $value) {
                    $sum_detail_voucher_invoice_expense += $value['amount'];
                }
            }
        }
        
        $sum_voucher_detail =  DB::table('voucher_details')->where('voucher_id', $voucherId)->sum('amount');

        return $sum_voucher_detail - $sum_detail_voucher_invoice_expense;
    }

    public function getPaymentStatusInvoiceBeforeVoucherCreated($dueDate, $downPayment = null)
    {
        $date = Carbon::now();
        $dueDate = Carbon::parse($dueDate);
        $downPayment = (int) $downPayment;

        if ($downPayment < 1) {
            return (true == $date->greaterThan($dueDate)) ? Invoice::STATUS_OVERDUE : Invoice::STATUS_OUTSTANDING;
        }
        
        return (true == $date->greaterThan($dueDate)) ? Invoice::STATUS_OVERDUE : Invoice::STATUS_PARTIAL_PAYMENT;
    }
    
    public function calculateTotalAmountInvoiceReceivable($companyId, $dataProducts, $discount = 0, $downPayment = 0, $nominalVat = 0)
    {
        $company = Company::find($companyId);


        // sum total price products
        $priceProducts = [];

        foreach ($dataProducts as $key => $value) {
            array_push($priceProducts, $value['total']);
        }

        $totalPriceProducts = array_sum($priceProducts);


        // calculate total ampunt invoice receivable
        $nominalVat = (true == $company->vat_enabled) ? $nominalVat : 0;
        $substract = array_sum([$discount, $downPayment]);
        return (($totalPriceProducts - $substract) + $nominalVat);
    }

    public function calculateTotalAmountInvoicePayable($companyId, $dataProducts, $discount = 0, $downPayment = 0, $nominalVat = 0, $withHoldingTax = 0) {
        $company = Company::find($companyId);


        // sum total price products
        $priceProducts = [];

        foreach ($dataProducts as $key => $value) {
            array_push($priceProducts, $value['total']);
        }

        $totalPriceProducts = array_sum($priceProducts);


        // calculate total ampunt invoice payable
        $substract = array_sum([$withHoldingTax, $discount, $downPayment]);
        return ($totalPriceProducts - $substract) + $nominalVat;
    }
}
