<?php

namespace App\Configurations\Finance;

use App\Models\Company;

class FinanceConfigurationDefault {

    private $company;

    public function __construct($id)
    {
        $this->company = Company::findOrFail($id);
    }

    public function execute()
    {
        DefaultTax::handle($this->company);
        DefaultInvoiceReceivable::handle($this->company);
        DefaultInvoicePayable::handle($this->company);
        DefaultVoucherAsset::handle($this->company);
        DefaultVoucherOtherExpense::handle($this->company);
        DefaultAccountArSales::handle($this->company);
        DefaultAccountCashEquivalents::handle($this->company);
        DefaultAccountGoodsSales::handle($this->company);
        DefaultAccountOperationalExpenses::handle($this->company);
        DefaultAccountReceivable::handle($this->company);
        DefaultAccountRevRecInAdv::handle($this->company);
        DefaultAccountSalesDiscounts::handle($this->company);
        DefaultAccountTradePayable::handle($this->company);
        DefaultAccountVatIn::handle($this->company);
        DefaultAccountVatOut::handle($this->company);
        DefaultAccountIncomeTax23Prepaid::handle($this->company);
        DefaultAccountIncomeTax23Payable::handle($this->company);
    }
}
