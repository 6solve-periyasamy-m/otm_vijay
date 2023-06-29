<?php

return [
    'result' => [
        'type' => [
            'flat_reduction' => 'Flat Reduction',
            'percentage_reduction' => 'Percentage Reduction',
            'free_component' => 'Free Component',
        ],
        'description' => [
            'flat_reduction' => 'An order of :total, will be adjusted by :reduction, and will now cost :after',
            'percentage_reduction' => 'An order of :total, will be reduced by :reduction, and will now cost :after',
            'free_component' => 'Free Component',
        ],
    ],
    'form' => [
        'create' => 'Create Voucher Code',
        'edit' => 'Create Voucher Code',
        'fields' => [
            'code' => 'Code',
            'name' => 'Name',
            'expiry' => 'Expiry',
            'description' => 'Description',
            'active' => 'Is the code active?',
            'global' => 'Can it be used on all tours?',
        ],
    ],
    'details' => [
        'code' => 'Code',
        'name' => 'Name',
        'expiry' => 'Expiry',
        'description' => 'Description',
        'active' => 'Is the code active?',
        'global' => 'Can it be used on all tours?',
        'buttons' => [
            'edit' => 'Edit Voucher',
            'delete' => 'Delete Voucher',
        ]
    ],
];
