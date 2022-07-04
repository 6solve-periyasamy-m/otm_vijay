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
        'cards' => [
            'customers' => [
                'header' => 'Travellers',
                'default' => 'Default Traveller',
                'lead' => 'Lead Traveller',
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
