<?php


return [
    'form' => [
        'title' => [
            'create' => 'Create Tour',
            'update' => 'Update Tour',
        ],
    ],
    'view' => [
        'buttons' => [
            'accommodation' => 'Edit Hotels',
        ],
    ],
    'costing' => [
        'view' => [
            'cards' => [
                'components' => [
                    'common' => [
                        'type' => 'Type',
                        'dates' => 'Dates',
                        'details' => 'Description',
                        'component_type' => 'Tour Component Type',
                        'sold' => 'Sold',
                        'available' => 'Available',
                        'price' => [
                            'purchase' => 'Purchase Price',
                            'tour' => 'Sales Price',
                            'margin' => 'Margin',
                        ],
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
            ],
        ],
    ],
];
