<?php

return [
    'table' => [
        'reference' => 'Reference',
        'package' => 'Tour',
        'description' => 'Description',
        'lead' => 'Lead Traveller',
        'email' => 'Email Address',
        'expiry' => 'Expiry Date',
        'status' => 'Quote Status',
        'notes' => 'Notes',
    ],
    'view' => [
        'title' => 'View Quote',
        'reference' => 'Quote Reference',
        'status' => 'Quote Status',
        'consultant' => 'Consultant',
        'name' => 'Quote Name',
        'expires' => 'Expires At',
        'starts' => 'Starts Date',
        'ends' => 'Ends Date',
        'description' => 'Description',
        'notes' => [
            'internal' => 'Internal Note',
            'external' => 'External Note',
        ],
        'lead' => [
            'name' => 'Lead Traveller Name',
            'contact' => 'Lead Contact Information'
        ],
        'locked' => 'This quote is currently linked to a tour, and has been locked to prevent editing an active package. If you wish to edit the components, please click the unlink button below.',
        'buttons' => [
            'edit' => 'Edit Quote',
            'add' => 'Add Components',
            'lock' => 'Lock Quote',
            'unlock' => 'Unlink Quote',
            'send' => 'Send Quote',
            'sent' => 'Mark Sent',
            'close' => 'Close Quote',
            'change' => 'Changes Required',
            'approve' => 'Approved',
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
                    'lead' => [
                        'header' => 'Lead Traveller Details',
                        'paying' => 'Travelling (Paid)',
                        'travelling' => 'Travelling (Free)',
                        'organizing' => 'Not Travelling or Paying'
                    ],
                    'header' => 'Cost Calculator',
                    'description' => 'Calculate cost for X travellers',
                    'purchase' => 'Purchase Price of Components (Approximate)',
                    'ctc' => 'Cost to Company',
                    'profit' => 'Profit (Per Person Profit) (Margin)',
                    'cost' => 'Total (Price per Person)',
                    'count' => 'How many additional travellers?',
                    'paying' => 'Paying',
                    'travelling' => 'Non-paying',
                    'convert' => 'Convert',
                    'costing' => 'Costs',
                    'send' => 'Send',
                    'preview' => 'Preview',
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
                ],
                'with-order' => 'With Order',
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
                    'sales_price' => 'Sales Price',
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
            ],
            'sent' => [
                'header' => 'Sent Versions',
                'when' => 'When',
                'email' => 'Sent to',
                'paying' => 'Paying Travellers',
                'travelling' => 'Non-Paying Travellers',
                'reference' => 'Reference'
            ],
            'sections' => [
                'header' => 'Quote Sections',
                'title' => 'Title',
                'body' => 'Body',
                'image' => 'Has Image?',
                'hidden' => 'Hidden?',
                'order' => 'Order'
            ],
        ]
    ],
    'status' => [
        'expired' => 'Expired',
        'not_sent' => 'Not Sent',
        'changes' => 'Requires Changes',
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
