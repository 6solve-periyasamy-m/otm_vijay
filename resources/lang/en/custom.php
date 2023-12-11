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
                'over' => 'Cancelled: Over-Refunded',
                'required' => 'Cancelled: Requires Refund',
                'none' => 'Cancelled: No Refund Required'
            ]
        ]
    ],
    'required' => [
        'symbol' => '*',
        'alt' => 'This field is required to submit'
    ],
    'table' => [
        'actions' => 'Actions',
    ],
    'bank' => [
        'name' => 'Bank Name',
        'form' => [
            'create' => 'Create Bank',
            'udpate' => 'Update Bank',
        ]
    ],
    'address' => [
        'line-1' => 'Address Line 1',
        'line-2' => 'Address Line 2',
        'line-3' => 'Address Line 3',
        'town' => 'Town',
        'region' => 'Region',
        'country' => 'Country',
        'postcode' => 'Postcode',
    ]
];
