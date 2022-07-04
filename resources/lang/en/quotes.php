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
    'status' => [
        'expired' => 'Expired',
        'not_sent' => 'Not Send',
        'awaiting' => 'Awaiting Response',
        'approved' => 'Approved - Pending Conversion',
        'converted' => 'Approved - Converted to Order',
        'closed' => 'Closed'
    ],
    'traveller' => [
        'prospect' => [
            'unset' => 'Details not provided'
        ],
    ],
];
