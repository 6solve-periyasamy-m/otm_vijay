<?php

return [
    'result' => [
        'type' => [
            'flat_reduction' => [
                'name' => 'Flat Reduction',
                'description' => 'An order of :total, will be adjusted by :reduction, and will now cost :after',
                'toast' => [
                    'success' => [
                        'title' => 'Created Successfully',
                        'body' => 'The flat reduction has been successfully created',
                    ],
                    'failed' => [
                        'title' => 'Failed to Create',
                        'body' => 'The flat reduction was unable to be created',
                    ],
                ],
            ],
            'percentage_reduction' => [
                'name' => 'Percentage Reduction',
                'description' => 'An order of :total, will be reduced by :reduction, and will now cost :after',
                'toast' => [
                    'success' => [
                        'title' => 'Created Successfully',
                        'body' => 'The percentage reduction has been successfully created',
                    ],
                    'failed' => [
                        'title' => 'Failed to Create',
                        'body' => 'The percentage reduction was unable to be created',
                    ],
                ],
            ],
            'free_component' => [
                'name' => 'Free Component',
                'description' => 'Free Component',
            ],
        ],
        'card' => [
            'title' => 'Executors',
            'create' => 'Create',
        ],
    ],
    'tour' => [
        'card' => [
            'title' => 'Linked Tours',
            'include' => 'Include',
            'exclude' => 'Exclude'
        ],
        'table' => [
            'name' => 'Name',
            'event' => 'Event',
            'included' => 'Allowed',
            'actions' => 'Actions',
        ],
        'error' => [
            'not-found' => [
                'title' => 'Tour not found',
                'body' => 'No tour with that ID was found'
            ]
        ]
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
