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
                    'components' => [
                        'accommodation' => 'Accommodation',
                        'activities' => 'Activities',
                        'flights' => 'Flights',
                        'transport' => 'Transport',
                        'merchandise' => 'Merchandise',
                        'total' => 'Total Per Person',
                        'approximate' => 'Amount is based on the purchase price of template accommodation and may differ',
                    ],
                    'header' => 'Cost Calculator',
                    'description' => 'Calculate cost for X travellers',
                    'purchase' => 'Purchase Price of Components (Approximate)',
                    'ctc' => 'Cost to Company',
                    'profit' => 'Profit (Per Person Profit) (Approximate)',
                    'cost' => 'Total (Price per Person)',
                    'count' => 'How many additional travellers?',
                    'paying' => 'Paying',
                    'travelling' => 'Non-paying'
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
            ],
            'components' => [
                'common' => [
                    'type' => 'Type',
                    'dates' => 'Dates',
                    'details' => 'Description',
                    'price' => 'Purchase Price',
                    'na' => 'Not Applicable',
                ],
                'tabs' => [
                    'accommodation' => 'Accommodation',
                    'activities' => 'Activities',
                    'flights' => 'Flights',
                    'transport' => 'Transport',
                    'extras' => 'Merchandise',
                    'summary' => 'All Components',
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
