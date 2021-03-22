<?php

use App\Models\Account;

return [
    'account' => [
        [
            'account_name' => 'kas',
            'account_code' => '1100',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::ASSETS
        ],
        [
            'account_name' => 'bank',
            'account_code' => '1200',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::ASSETS
        ],
        [
            'account_name' => 'piutang',
            'account_code' => '1300',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::ASSETS
        ],
        [
            'account_name' => 'pajak dibayar dimuka',
            'account_code' => '1400',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::ASSETS
        ],
        [
            'account_name' => 'persediaan',
            'account_code' => '1400',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::ASSETS
        ],
        [
            'account_name' => 'uang muka',
            'account_code' => '1500',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::ASSETS
        ],
        [
            'account_name' => 'utang',
            'account_code' => '2100',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::LIABILITIES
        ],
        [
            'account_name' => 'uang muka',
            'account_code' => '2200',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::LIABILITIES
        ],
        [
            'account_name' => 'utang pajak',
            'account_code' => '2300',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::LIABILITIES
        ],
        [
            'account_name' => 'saham',
            'account_code' => '3100',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::CAPITALS
        ],
        [
            'account_name' => 'laba',
            'account_code' => '3200',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::CAPITALS
        ],
        [
            'account_name' => 'pendapatan usaha',
            'account_code' => '4100',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::INCOME
        ],
        [
            'account_name' => 'pendapatan lain',
            'account_code' => '4200',
            'level' => 1,
            'description' => null,
            'balance' => 'credit',
            'account_type' => Account::INCOME
        ],
        [
            'account_name' => 'beban atas pendapatan',
            'account_code' => '5100',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::EXPENSES
        ],
        [
            'account_name' => 'beban operasional',
            'account_code' => '5200',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::EXPENSES
        ],
        [
            'account_name' => 'beban lain',
            'account_code' => '5300',
            'level' => 1,
            'description' => null,
            'balance' => 'debit',
            'account_type' => Account::EXPENSES
        ],
        [
            'account_name' => 'kas kecil',
            'account_code' => '1110',
            'level' => 2,
            'description' => null,
            'parent_name' => 'kas'
        ],
        [
            'account_name' => 'kas besar',
            'account_code' => '1120',
            'level' => 2,
            'description' => null,
            'parent_name' => 'kas'
        ],
        [
            'account_name' => 'bank bca',
            'account_code' => '1210',
            'level' => 2,
            'description' => null,
            'parent_name' => 'bank',
        ],
        [
            'account_name' => 'bank bni',
            'account_code' => '1220',
            'level' => 2,
            'description' => null,
            'parent_name' => 'bank',
        ],
        [
            'account_name' => 'pajak dibayar dimuka',
            'account_code' => '1410',
            'level' => 2,
            'description' => null,
            'parent_name' => 'pajak dibayar dimuka',
        ],
        [
            'account_name' => 'utang pembelian',
            'account_code' => '2110',
            'level' => 2,
            'description' => null,
            'parent_name' => 'utang',
        ],
        [
            'account_name' => 'kelebihan bayar',
            'account_code' => '2120',
            'level' => 2,
            'description' => 'utang karena lebih bayar',
            'parent_name' => 'utang',
        ],
        [
            'account_name' => 'beban listrik',
            'account_code' => '5210',
            'level' => 2,
            'description' => null,
            'parent_name' => 'beban operasional'
        ],
        [
            'account_name' => 'beban air',
            'account_code' => '5220',
            'level' => 2,
            'description' => null,
            'parent_name' => 'beban operasional'
        ],
        [
            'account_name' => 'biaya admin bank',
            'account_code' => '5310',
            'level' => 2,
            'description' => null,
            'parent_name' => 'beban lain'
        ],
        [
            'account_name' => 'penjualan produk',
            'account_code' => '4110',
            'level' => 2,
            'description' => null,
            'parent_name' => 'pendapatan usaha'
        ],
        [
            'account_name' => 'potongan penjualan',
            'account_code' => '4120',
            'level' => 2,
            'description' => null,
            'parent_name' => 'pendapatan usaha'
        ],
        [
            'account_name' => 'piutang usaha penjualan',
            'account_code' => '1310',
            'level' => 2,
            'description' => null,
            'parent_name' => 'piutang'
        ],
        [
            'account_name' => 'harga pokok penjualan',
            'account_code' => '5110',
            'level' => 2,
            'description' => null,
            'parent_name' => 'beban atas pendapatan'
        ],
        [
            'account_name' => 'potongan pembelian',
            'account_code' => '5310',
            'level' => 2,
            'description' => null,
            'parent_name' => 'beban lain'
        ],
    ]
];
