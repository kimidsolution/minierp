<?php

use App\Models\Account;

return [
    [
        'id' => Account::ASSETS,
        'name' => 'Assets',
        'balance' => 'debit'
    ],
    [
        'id' => Account::LIABILITIES,
        'name' => 'Liabilities',
        'balance' => 'credit'
    ],
    [
        'id' => Account::CAPITALS,
        'name' => 'Capitals',
        'balance' => 'credit'
    ],
    [
        'id' => Account::INCOME,
        'name' => 'Income',
        'balance' => 'credit'
    ],
    [
        'id' => Account::EXPENSES,
        'name' => 'Expenses',
        'balance' => 'debit'
    ],
    [
        'id' => Account::COGS,
        'name' => 'COGS (Cost of Goods Sold)',
        'balance' => 'credit'
    ],
    [
        'id' => Account::OTHER_INCOME,
        'name' => 'Other Income',
        'balance' => 'credit'
    ],
    [
        'id' => Account::OTHER_EXPENSES,
        'name' => 'Other Expenses',
        'balance' => 'debit'
    ]
];
