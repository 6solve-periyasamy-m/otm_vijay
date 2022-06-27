<?php

return [
    'used-in-tour' => 'This :model cannot be deleted, as it is used in a tour',
    'used-elsewhere' => 'This :model cannot be deleted, as it is used in a(n) :parent',
    'order' => [
        'status' => [
            'full' => 'Paid in Full',
            'outstanding' => 'Balance Outstanding',
            'overdue' => 'Payment Overdue',
            'overpaid' => 'Overpaid',
            'occupancy' => 'Missing Occupancy',
            'cancelled' => [
                'deposit' => 'Cancelled: Deposit Held',
                'full' => 'Cancelled: Fully Refunded',
                'required' => 'Cancelled: Requires Refund',
            ]
        ]
    ],
    'table' => [
        'actions' => 'Actions',
    ]
];
