<?php


return [
    'form' => [
        'title' => [
            'create' => 'Create Organization',
            'update' => 'Update Organization'
        ],
        'fields' => [
            'name' => 'Name',
            'commission' => 'Default Commission (%)',
            'contact' => [
                'email' => 'Contact Email',
                'number' => 'Contact Phone Number'
            ],
            'notes' => [
                'internal' => 'Internal Notes',
                'external' => 'External Notes'
            ],
            'address' => [
                'delivery' => [
                    'title' => 'Delivery Address',
                    'line_1' => 'Line 1',
                    'line_2' => 'Line 2',
                    'town' => 'Town',
                    'region' => 'Region',
                    'country' => 'Country',
                    'postcode' => 'Postcode'
                ],
                'billing' => [
                    'title' => 'Billing Address',
                    'line_1' => 'Line 1',
                    'line_2' => 'Line 2',
                    'town' => 'Town',
                    'region' => 'Region',
                    'country' => 'Country',
                    'postcode' => 'Postcode'
                ],
                'clone' => 'Use as billing address?'
            ]
        ]
    ],
    'table' => [
        'title' => 'All Organizations',
        'columns' => [
            'name' => 'Name',
            'email' => 'Contact Email',
            'telephone' => 'Contact Number',
            'customers' => 'Members',
            'orders' => 'Orders',
            'quotes' => 'Quotes',
        ],
    ],
];