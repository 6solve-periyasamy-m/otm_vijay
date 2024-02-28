<?php


return [
    'event' => [
        'column' => [
            'name' => [
                'name' => 'Event Name',
                'description' => 'Name of the Event',
            ],
            'description' => [
                'name' => 'Event Description',
                'description' => 'Description of the Event',
            ],
            'start' => [
                'name' => 'Event Start',
                'description' => 'Start Date of the Event',
            ],
            'end' => [
                'name' => 'Event End',
                'description' => 'End Date of the Event',
            ],
            'notes' => [
                'name' => 'Event Notes',
                'description' => 'Notes for the Event',
            ],
            'tours' => [
                'name' => 'Tour Count',
                'description' => 'Number of tours marked for the Event',
            ],
        ],
    ],
    'tour' => [
        'column' => [
            'name' => [
                'name' => 'Tour Name',
                'description' => 'The name of the tour the order is placed for',
            ],
            'description' => [
                'name' => 'Tour Description',
                'description' => 'The description of the tour the order is placed for',
            ],
            'base_price' => [
                'name' => 'Base Price Per Person',
                'description' => 'The base price per person of the tour'
            ],
            'margin' => [
                'name' => 'Margin',
                'description' => 'The set margin for the tour'
            ],
            'single_occupancy' => [
                'name' => 'Single Occupancy',
                'description' => 'Default single occupancy surcharge for the tour'
            ],
            'deposit' => [
                'name' => 'Tour Deposit',
                'description' => 'Default deposit for the tour'
            ],
            'booking_fee' => [
                'name' => 'Tour Booking Fee',
                'description' => 'Default booking fee for the tour'
            ],
            'total_stock' => [
                'name' => 'Tour Total Stock',
                'description' => 'The total amount of stock that can be used for this tour',
            ],
            'used_stock' => [
                'name' => 'Tour Used Stock',
                'description' => 'The total number of uncancelled orders placed for this tour',
            ],
            'category' => [
                'name' => 'Tour Category',
                'description' => 'The category assigned to the tour',
            ],
            'active' => [
                'name' => 'Active?',
                'description' => 'Is the tour currently active?'
            ],
            'from' => [
                'name' => 'Tour Start Date',
                'description' => 'Start date for the tour',
            ],
            'to' => [
                'name' => 'Tour End Date',
                'description' => 'End date for the tour',
            ],
            'final_payment' => [
                'name' => 'Tour Final Payment Date',
                'description' => 'When is the final payment due for the tour',
            ],
        ],
    ],
    'order' => [
        'column' => [
            
        ],
    ],
];
