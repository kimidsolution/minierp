@php $isAdmin = Illuminate\Support\Facades\Gate::allows('isAdmin') @endphp
{{-- enlarge-menu --}}
<!-- leftbar-tab-menu -->
<div class="leftbar-tab-menu">
    <div class="main-icon-menu">
        <div class="content-logo">
            <a href="{{ route('home') }}" class="logo logo-metrica d-block text-center">
                <span>
                    <img src="{{ URL::asset('metrica/assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm" />
                </span>
            </a>
        </div>
        <nav class="nav">
            <a href="#MetricaDashboard" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Dashboard" data-trigger="hover">
                <i data-feather="clipboard" class="align-self-center menu-icon icon-dual"></i>
            </a>
            <a href="#MetricaModuleAdmin" class="nav-link" data-toggle="tooltip-custom" data-placement="right" data-trigger="hover" title="" data-original-title="Module Admin">
                <i data-feather="monitor" class="align-self-center menu-icon icon-dual"></i>
            </a>
            <a href="#MetricaDataMaster" class="nav-link" data-toggle="tooltip-custom" data-placement="right" data-trigger="hover" title="" data-original-title="Data Master">
                <i data-feather="database" class="align-self-center menu-icon icon-dual"></i>
            </a>
            <a href="#MetricaTransaction" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Data Transaction" data-trigger="hover">
                <i data-feather="shopping-cart" class="align-self-center menu-icon icon-dual"></i>
            </a>
            <a href="#MetricaReport" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Data Reports" data-trigger="hover">
                <i data-feather="book" class="align-self-center menu-icon icon-dual"></i>
            </a>
            <a href="#MetricaConfig" class="nav-link" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Configuration" data-trigger="hover">
                <i data-feather="settings" class="align-self-center menu-icon icon-dual"></i>
            </a>
        </nav>
        <!--end nav-->
    </div>
    <!--end main-icon-menu-->

    <div class="main-menu-inner">
        <!-- LOGO -->
        <div class="topbar-left">
            <a href="{{ route('home') }}" class="logo">
                <span>
                    <h3 style="font-size: 1.3rem; padding: 15px;" alt="logo-large" class="logo-lg logo-dark">Sempoa ERP</h3>
                    <h3 style="font-size: 1.3rem; padding: 15px;" alt="logo-large" class="logo-lg logo-light">Sempoa ERP</h3>
                </span>
            </a>
        </div>
        <!--end logo-->
        <div class="menu-body slimscroll">
            <div id="MetricaDashboard" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Dashboard</h6>
                </div>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Dashboard</a></li>
                </ul>
            </div>
            <div id="MetricaModuleAdmin" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Admin Module</h6>
                </div>
                <ul class="nav">
                    @if ($isAdmin)
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.companies.index') }}">Company</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.currencies.index') }}">Currency</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a></li>
                </ul>
            </div><!-- end Module Admin -->
            <div id="MetricaDataMaster" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Data Master</h6>
                </div>
                <ul class="nav metismenu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('master.partner.index') }}">Partner</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('finance.accounts.index') }}">Accounts</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('master.product.index') }}">Products</a></li>
                </ul>
            </div><!-- end Module Admin -->
            <div id="MetricaTransaction" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Data Transaction</h6>
                </div>
                <ul class="nav metismenu">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="w-100">Transactions</span>
                            <span class="menu-arrow">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="{{ route('finance.transactions.receivable.index') }}">Receivable</a></li>
                            <li><a href="{{ route('finance.transactions.payable.index') }}">Payable</a></li>
                            <li><a href="{{ route('finance.transactions.import.index') }}">Import</a></li>
                        </ul>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="#"><span class="w-100">Invoices</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="{{ route('finance.invoices.receivable.index') }}">Customer</a></li>
                            <li><a href="{{ route('finance.invoices.payable.index') }}">Vendor</a></li>
                        </ul>
                    </li><!--end nav-item-->
                    <li class="nav-item"><a class="nav-link" href="{{ route('finance.vouchers.index') }}">Voucher</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('finance.expenses.index') }}">Expenses</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route('finance.revenues.index') }}">Revenue</a></li> --}}
                </ul>
            </div><!-- end Invoice -->
            <div id="MetricaReport" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Data Report</h6>
                </div>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route('finance.report.general-ledger') }}">General Ledger</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('finance.report.profit-loss') }}">Profit And Loss</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('finance.report.trial-balance') }}">Trial Balance</a></li>
                </ul>
            </div><!-- end report -->
            <div id="MetricaConfig" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Configuration</h6>
                </div>
                <ul class="nav metismenu">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="w-100">Finance</span>
                            <span class="menu-arrow">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li>
                                <a href="{{ route('configuration.finance.accounts.index') }}">Accounts</a>
                            </li>
                        </ul>
                    </li><!--end nav-item-->
                </ul>
            </div><!-- end config -->
        </div>
        <!--end menu-body-->
    </div>
    <!-- end main-menu-inner-->
</div>
<!-- end leftbar-tab-menu-->
