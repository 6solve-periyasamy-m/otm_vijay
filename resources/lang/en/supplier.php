<?php


return [
    'form' => [
        'title' => [
            'create' => 'Create Supplier',
            'update' => 'Update Supplier',
        ],
        'fields' => [
            'name' => 'Name',
            'telephone' => 'Contact Number',
            'email' => 'Contact Email',
            'website' => 'Website',
            'currency' => 'Trading Currency',
            'exchange' => 'Agreed Exchange Rate',
            'notes' => 'Notes',
            'address' => [
                'line-1' => 'Address Line 1',
                'line-2' => 'Address Line 2',
                'town' => 'Town',
                'region' => 'Region',
                'country' => 'Country',
                'postcode' => 'Postcode'
            ]
        ]
    ],
    'details' => [
        'name' => 'Supplier Name',
        'contact' => [
            'address' => 'Address',
            'website' => 'Website',
            'telephone' => 'Telephone',
            'email' => 'Email Address',
        ],
        'currency' => [
            'name' => 'Trading Currency',
            'none' => 'No currency set'
        ],
        'exchange' => [
            'name' => 'Agreed Exchange',
            'none' => 'No rate agreed'
        ],
        'buttons' => [
            'edit' => 'Edit Supplier',
            'delete' => 'Delete Supplier',
        ]
    ],
    'associate' => [
        'title' => 'Associates',
        'buttons' => [
            'create' => 'Create Associate'
        ],
        'form' => [
            'title' => [
                'create' => 'Create Associate',
                'update' => 'Update Associate',
            ],
            'fields' => [
                'name' => 'Name',
                'job' => 'Job Title',
                'email' => 'Email',
                'primary_phone' => 'Primary Phone',
                'alternative_phone' => 'Alternative Phone',
                'notes' => 'Notes',
            ],
        ],
        'table' => [
            'name' => 'Name',
            'job_title' => 'Position',
            'email' => 'Email',
            'primary_phone' => 'Primary Number',
            'alternative_phone' => 'Alternative Number',
            'notes' => 'Notes'
        ]
    ],
    'contract' => [
        'title' => 'Contracts',
        'create' => 'Create Contract',
        'details' => [
            'buttons' => [
                'edit' => 'Edit Contract',
                'link' => 'Link Components',
                'delete' => 'Delete Contract',
            ],
            'number' => 'Purchase Order Number',
            'supplier_reference' => 'Supplier Reference Number',
            'currency' => 'Currency',
            'total' => 'Total Contract Value',
            'tax' => 'Tax Rate',
            'notes' => 'Notes',
        ],
        'form' => [
            'title' => [
                'create' => 'Create Contract',
                'update' => 'Update Contract',
            ],
            'fields' => [
                'order_number' => 'Purchase Order Number',
                'reference_number' => 'Supplier Reference Number',
                'currency' => 'Currency',
                'net_cost' => 'Local Cost (Not Saved)',
                'exchange' => 'Agreed Exchange',
                'total_cost' => 'Total Cost',
                'confirmed' => 'Confirmed',
                'tax_rate' => 'Tax Rate (%)',
                'before_tax' => 'Cost Before Tax',
                'notes' => 'Notes',
            ],
        ],
        'payment' => [
            'create' => 'Create Payment',
            'form' => [
                'title' => [
                    'create' => 'Create Contract Payment',
                    'update' => 'Update Contract Payment',
                ],
                'fields' => [
                    'amount' => 'Amount',
                    'paid' => 'Paid On',
                    'exchange_rate' => 'Exchange Rate',
                    'notes' => 'Notes',
                ],
            ],
        ],
        'installment' => [
            'create' => 'Create Instalment',
            'form' => [
                'title' => [
                    'create' => 'Create Contract Instalment',
                    'update' => 'Update Contract Instalment',
                ],
                'fields' => [
                    'amount' => 'Amount',
                    'due' => 'Due On',
                ],
            ],
        ],
    ],
];
