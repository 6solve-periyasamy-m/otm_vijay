<?php

return [
    'table' => [
        'reference' => 'Reference',
        'package' => 'Tour',
        'lead' => 'Lead Traveller',
        'email' => 'Email Address',
        'status' => 'Quote Status',
        'notes' => 'Notes',
    ],
    'view' => [
        'title' => 'View Quote',
        'reference' => 'Quote Reference',
        'status' => 'Quote Status',
        'name' => 'Quote Name',
        'expires' => 'Expires At',
        'starts' => 'Starts Date',
        'ends' => 'Ends Date',
        'lead' => [
            'name' => 'Lead Traveller Name',
            'contact' => 'Lead Contact Information'
        ],
        'cards' => [
            'quick' => [
                'header' => 'Quick Information',
                'calculator' => [
                    'header' => 'Cost Calculator',
                    'description' => 'Calculate cost for X travellers',
                    'cost' => 'Total (Price per Person)',
                    'count' => 'Customer Count',
                ],
            ],
            'installments' => [
                'header' => 'Instalments',
                'table' => [
                    'type' => 'Type',
                    'due' => 'Due',
                    'amount' => 'Amount',
                ],
                'form' => [
                    'due' => 'Due On',
                    'amount' => 'Amount',
                    'create' => 'Create',
                    'refresh' => 'Resync'
                ],
                'types' => [
                    'deposit' => 'Deposit',
                    'installment' => 'Instalment',
                    'remaining' => 'Remaining Balance',
                ]
            ],
            'price-points' => [
                'header' => 'Price Matrix',
                'table' => [
                    'quantity' => 'Quantity',
                    'cost' => 'Price Per Person',
                ],
                'form' => [
                    'quantity' => 'Quantity',
                    'cost' => 'Price Per Person',
                    'create' => 'Create',
                ],
            ]
        ]
    ],
    'status' => [
        'expired' => 'Expired',
        'not_sent' => 'Not Sent',
        'awaiting' => 'Awaiting Response',
        'approved' => 'Approved - Pending Conversion',
        'converted' => 'Approved - Converted to Order',
        'closed' => 'Closed',
        'unknown' => 'Status Unknown'
    ],
    'traveller' => [
        'prospect' => [
            'unset' => 'Details not provided'
        ],
    ],
];
