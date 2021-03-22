<?php

return [
    'account_used' => [
        'receivable' => [
            'remaining' => [
                'name' => 'AR Sales',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ],
            'over_payment' => [
                'name' => 'Revenue Received In Advanced',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'credit'
            ]
        ],
        'payable' => [
            'over_payment' => [
                'name' => 'Account Receivable',
                'debit_amount' => 0,
                'credit_amount' => 0,
                'balance' => 'debit'
            ]
        ]
    ]
];