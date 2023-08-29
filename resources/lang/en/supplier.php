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
];
