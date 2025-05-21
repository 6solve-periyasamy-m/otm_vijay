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
            'reference' => [
                'name' => 'Booking Reference',
                'description' => 'The booking reference for the order',
            ],
            'travellers' => [
                'name' => 'Travellers',
                'description' => 'The number of travellers on the order',
            ],
            'deposit' => [
                'name' => 'Order Deposit',
                'description' => 'The amount of deposit paid on the order',
            ],
            'booking_fee' => [
                'name' => 'Order Booking Fee',
                'description' => 'The amount of deposit paid on the order',
            ],
            'status' => [
                'name' => 'Status',
                'description' => 'The current status of the order'
            ],
            'commission' => [
                'percentage' => [
                    'name' => 'Commission (%)',
                    'description' => 'The percentage of the order value to be taken as commission'
                ],
                'amount' => [
                    'name' => 'Commission Amount',
                    'description' => 'The amount of commission to be taken'
                ],
            ],
            'ordered' => [
                'name' => 'Ordered',
                'description' => 'When the order was placed'
            ],
            'traveller-names' => [
                'name' => 'Traveller Names',
                'description' => 'Names of all travellers on the order'
            ],
            'cancelled' => [
                'name' => 'Cancelled',
                'description' => 'Is the order currently cancelled'
            ],
            'internal_notes' => [
                'name' => 'Internal Notes',
                'description' => 'Any internal notes visible only to staff'
            ],
            'external_notes' => [
                'name' => 'External Notes',
                'description' => 'Any external notes visible to staff and customers'
            ],
            'invoice_footer' => [
                'name' => 'Invoice Footer',
                'description' => 'The footer message for the order invoice'
            ],
            'paid' => [
                'name' => 'Amount Paid',
                'description' => 'The total amount paid so far on the order'
            ],
            'cost' => [
                'name' => 'Total Cost',
                'description' => 'The total cost of the order before adjustments and commission'
            ],
            'total_owed' => [
                'name' => 'Total Owed',
                'description' => 'The total amount owed by the customer after adjustments and commission'
            ],
            'remaining' => [
                'name' => 'Remaining',
                'description' => 'The amount remaining to be paid on the order'
            ],
            'cost_to_company' => [
                'name' => 'Cost to Company',
                'description' => 'The total cost to company for the order'
            ],
            'profit' => [
                'name' => 'Current Profit',
                'description' => 'The total profit to company for the order'
            ],
            'next_payment' => [
                'due' => [
                    'name' => 'Next Installment Due',
                    'description' => 'When is the next installment due'
                ],
                'amount' => [
                    'name' => 'Next Installment Amount',
                    'description' => 'The total due for the next installment'
                ],
                'remaining' => [
                    'name' => 'Next Installment Remaining',
                    'description' => 'The total amount remaining on the next installment'
                ],
            ],
            'lead_booker' => [
                'first_name' => [
                    'name' => 'Lead Booker First Name',
                    'description' => 'First name of the lead booker'
                ],
                'middle_names' => [
                    'name' => 'Lead Booker Middle Names',
                    'description' => 'Middle names of the lead booker'
                ],
                'last_name' => [
                    'name' => 'Lead Booker Last Name',
                    'description' => 'Last name of the lead booker'
                ],
                'home_address' => [
                    'name' => 'Lead Booker Home Address',
                    'description' => 'Home address of the lead booker'
                ],
                'billing_address' => [
                    'name' => 'Lead Booker Billing Address',
                    'description' => 'Billing address of the lead booker'
                ],
                'email_address' => [
                    'name' => 'Lead Booker Email',
                    'description' => 'Email address of the lead booker'
                ],
                'mobile_number' => [
                    'name' => 'Lead Booker Mobile Number',
                    'description' => 'Mobile number of the lead booker'
                ],
            ],
            'consultant' => [
                'name' => [
                    'name' => 'Consultant Name',
                    'description' => 'The name of the consultant for the order'
                ],
                'email' => [
                    'name' => 'Consultant Email',
                    'description' => 'The email address of the consultant'
                ],
            ],
            'organization' => [
                'name' => [
                    'name' => 'Organization Name',
                    'description' => 'The name of the organization for the order'
                ],
            ],
            'agent' => [
                'first_name' => [
                    'name' => 'Agent First Name',
                    'description' => 'First name of the agent'
                ],
                'last_name' => [
                    'name' => 'Agent Last Name',
                    'description' => 'Last name of the agent'
                ],
                'email' => [
                    'name' => 'Agent Email',
                    'description' => 'The email address of the agent'
                ],
            ],
        ],
    ],
];
