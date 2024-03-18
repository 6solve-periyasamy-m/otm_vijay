<?php


return [
    'installment' => [
        'deposit' => [
            'single' => 'Deposit: :amount x :travellers Traveller = :total',
            'multiple' => 'Deposit: :amount x :travellers Travellers = :total',
        ],
        'installment' => [
            'single' => 'Instalment: :amount x :travellers Traveller = :total',
            'multiple' => 'Instalment: :amount x :travellers Travellers = :total',
        ],
        'booking-fee' => 'Booking Fee: :amount',
        'remaining' => 'Remaining Balance: :amount'
    ],
    'customer' => [
        'billable' => [
            'base' => 'Base Package Components',
            'surcharge' => 'Single Occupancy Surcharge',
        ],
    ],
    'group' => [
        'name' => 'Group: :members',
    ]
];