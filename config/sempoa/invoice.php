<?php

return [
    'account_used' => [
        'sales' => [
            'remaining' => [
                'name' => 'piutang usaha penjualan',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'discount' => [
                'name' => 'potongan penjualan',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'tax' => [
                'name' => 'utang pajak',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ],
            'amount' => [
                'name' => 'penjualan produk',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ]
        ],
        'purchases' => [
            'remaining' => [
                'name' => 'utang pembelian',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ],
            'discount' => [
                'name' => 'potongan pembelian',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ],
            'amount' => [
                'name' => 'harga pokok penjualan',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'tax' => [
                'name' => 'pajak dibayar dimuka',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ]
        ],
        'receivable' => [
            'amount' => [
                'name' => 'Goods Sales',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ],
            'remaining' => [
                'name' => 'AR Sales',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'discount' => [
                'name' => 'Sales Discounts',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'income_tax' => [
                'name' => 'Prepaid Income Tax Article 23',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ]
        ],
        'payable' => [
            'income_tax' => [
                'name' => 'Income Tax Payable Article 23',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ],
            'vat' => [
                'name' => 'Vat In',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'remaining' => [
                'name' => 'Trade Payable',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ],
            'amount' => [
                'name' => 'Operational Expenses',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
        ]
    ]
];