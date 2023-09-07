<?php


return [
    'form' => [
        'title' => [
            'create' => 'Create Organization',
            'update' => 'Update Organization'
        ],
        'fields' => [
            'name' => 'Name',
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
                    'line_1' => 'Line 1',
                    'line_2' => 'Line 2',
                    'town' => 'Town',
                    'region' => 'Region',
                    'country' => 'Country',
                    'postcode' => 'Postcode'
                ],
                'billing' => [
                    'line_1' => 'Line 1',
                    'line_2' => 'Line 2',
                    'town' => 'Town',
                    'region' => 'Region',
                    'country' => 'Country',
                    'postcode' => 'Postcode'
                ],
                'clone' => 'Billing is Delivery?'
            ]
        ]
    ]
];