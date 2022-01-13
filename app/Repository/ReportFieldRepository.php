<?php

namespace App\Repository;

class ReportFieldRepository
{
    public static function getAccommodationFields(): array
    {
        return [
            0 => [
                'class' => 'Accommodation',
                'type' => 'component',
                'fields' => [
                    'name' => [
                        'name' => 'Name',
                        'method' => 'name',
                    ],
                    'description' => [
                        'name' => 'Description',
                        'method' => 'description',
                    ],
                    'audit_date' => [
                        'name' => 'Audit Date',
                        'method' => 'audit_date',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'address' => [
                        'name' => 'Address',
                        'method' => 'address',
                    ],
                ],
            ],
            1 => [
                'class' => 'AccommodationInventory',
                'type' => 'inventory',
                'fields' => [
                    'room_type' => [
                        'name' => 'Room Type',
                        'method' => 'roomType',
                    ],
                    'board_type' => [
                        'name' => 'Board Type',
                        'method' => 'boardType',
                    ],
                    'check_in' => [
                        'name' => 'Check In',
                        'method' => 'check_in',
                    ],
                    'check_out' => [
                        'name' => 'Check Out',
                        'method' => 'check_out',
                    ],
                    'check_in_time_confirmed' => [
                        'name' => 'Check In Time Confirmed',
                        'method' => 'check_in_time_confirmed'
                    ],
                    'check_out_time_confirmed' => [
                        'name' => 'Check Out Time Confirmed',
                        'method' => 'check_out_time_confirmed'
                    ],
                    'fit_selectable' => [
                        'name' => 'FIT Selectable',
                        'method' => 'fit_selectable',
                    ],
                    'total_stock' => [
                        'name' => 'Total Stock',
                        'method' => 'stock',
                    ],
                    'used_stock' => [
                        'name' => 'Used Stock',
                        'method' => 'used_stock',
                    ],
                    'purchase_price' => [
                        'name' => 'Purchase Price',
                        'method' => 'purchase_price',
                    ],
                    'sales_price' => [
                        'name' => 'Sales Price',
                        'method' => 'sales_price',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                    'tour_count' => [
                        'name' => 'Used on Tours',
                        'method' => 'used_on_tour_count',
                    ],
                ],
            ],
            2 => [
                'class' => 'AccommodationInventoryTour',
                'type' => 'tour',
                'fields' => [
                    'tour_sales_price' => [
                        'name' => 'Tour Sales Price',
                        'method' => 'tour_sales_price',
                    ],
                    'tour_component_type' => [
                        'name' => 'Tour Component Type',
                        'method' => 'tour_component_type',
                    ],
                    'tour_name' => [
                        'name' => 'Tour Name',
                        'method' => 'tour_name'
                    ],
                ]
            ],
        ];
    }

    public static function getActivityFields(): array
    {
        return [
            0 => [
                'class' => 'Activity',
                'type' => 'component',
                'fields' => [
                    'name' => [
                        'name' => 'Name',
                        'method' => 'name',
                    ],
                    'description' => [
                        'name' => 'Description',
                        'method' => 'description',
                    ],
                    'activity_type' => [
                        'name' => 'Activity Type',
                        'method' => 'activityType',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'address' => [
                        'name' => 'Address',
                        'method' => 'address',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'ActivityInventory',
                'type' => 'inventory',
                'fields' => [
                    'ticket_type' => [
                        'name' => 'Ticket Type',
                        'method' => 'ticketType',
                    ],
                    'starts_at' => [
                        'name' => 'Starts At',
                        'method' => 'starts_at',
                    ],
                    'ends_at' => [
                        'name' => 'Ends At',
                        'method' => 'ends_at',
                    ],
                    'fit_selectable' => [
                        'name' => 'FIT Selectable',
                        'method' => 'fit_selectable',
                    ],
                    'total_stock' => [
                        'name' => 'Total Stock',
                        'method' => 'stock',
                    ],
                    'used_stock' => [
                        'name' => 'Used Stock',
                        'method' => 'used_stock',
                    ],
                    'purchase_price' => [
                        'name' => 'Purchase Price',
                        'method' => 'purchase_price',
                    ],
                    'sales_price' => [
                        'name' => 'Sales Price',
                        'method' => 'sales_price',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                    'tour_count' => [
                        'name' => 'Used on Tours',
                        'method' => 'used_on_tour_count',
                    ],
                ],
            ],
            2 => [
                'class' => 'ActivityInventoryTour',
                'type' => 'tour',
                'fields' => [
                    'tour_sales_price' => [
                        'name' => 'Tour Sales Price',
                        'method' => 'tour_sales_price',
                    ],
                    'tour_component_type' => [
                        'name' => 'Tour Component Type',
                        'method' => 'tour_component_type',
                    ],
                    'tour_name' => [
                        'name' => 'Tour Name',
                        'method' => 'tour_name'
                    ],
                ]
            ],
        ];
    }

    public static function getFlightFields(): array
    {
        return [
            0 => [
                'class' => 'Flight',
                'type' => 'component',
                'fields' => [
                    'departure_airport' => [
                        'name' => 'Departure Airport',
                        'method' => 'departureAirport',
                    ],
                    'arrival_airport' => [
                        'name' => 'Arrival Airport',
                        'method' => 'arrivalAirport',
                    ],
                    'available_after' => [
                        'name' => 'Available After',
                        'method' => 'available_after',
                    ],
                    'is_domestic' => [
                        'name' => 'Is Domestic',
                        'method' => 'is_domestic',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'FlightInventory',
                'type' => 'inventory',
                'fields' => [
                    'flight_number' => [
                        'name' => 'Flight Number',
                        'method' => 'flight_number',
                    ],
                    'travel_class' => [
                        'name' => 'Travel Class',
                        'method' => 'travelClass',
                    ],
                    'check_in' => [
                        'name' => 'Check In',
                        'method' => 'check_in',
                    ],
                    'starts_at' => [
                        'name' => 'Departs At',
                        'method' => 'departs_at',
                    ],
                    'ends_at' => [
                        'name' => 'Arrives At',
                        'method' => 'arrives_at',
                    ],
                    'fit_selectable' => [
                        'name' => 'FIT Selectable',
                        'method' => 'fit_selectable',
                    ],
                    'total_stock' => [
                        'name' => 'Total Stock',
                        'method' => 'stock',
                    ],
                    'used_stock' => [
                        'name' => 'Used Stock',
                        'method' => 'used_stock',
                    ],
                    'purchase_price' => [
                        'name' => 'Purchase Price',
                        'method' => 'purchase_price',
                    ],
                    'sales_price' => [
                        'name' => 'Sales Price',
                        'method' => 'sales_price',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                    'tour_count' => [
                        'name' => 'Used on Tours',
                        'method' => 'used_on_tour_count',
                    ],
                ],
            ],
            2 => [
                'class' => 'FlightInventoryTour',
                'type' => 'tour',
                'fields' => [
                    'tour_sales_price' => [
                        'name' => 'Tour Sales Price',
                        'method' => 'tour_sales_price',
                    ],
                    'tour_component_type' => [
                        'name' => 'Tour Component Type',
                        'method' => 'tour_component_type',
                    ],
                    'flight_type' => [
                        'name' => 'Flight Type',
                        'method' => 'flight_type',
                    ],
                    'tour_name' => [
                        'name' => 'Tour Name',
                        'method' => 'tour_name'
                    ],
                ]
            ],
        ];
    }

    public static function getTransportFields(): array
    {
        return [
            0 => [
                'class' => 'Transport',
                'type' => 'component',
                'fields' => [
                    'name' => [
                        'name' => 'Name',
                        'method' => 'name',
                    ],
                    'operator' => [
                        'name' => 'Operator',
                        'method' => 'operator',
                    ],
                    'description' => [
                        'name' => 'Description',
                        'method' => 'description',
                    ],
                    'departure_address' => [
                        'name' => 'Departure Address',
                        'method' => 'departureAddress',
                    ],
                    'arrival_address' => [
                        'name' => 'Arrival Address',
                        'method' => 'arrivalAddress',
                    ],
                    'transport_type' => [
                        'name' => 'Transport Type',
                        'method' => 'transportType',
                    ],
                    'is_domestic' => [
                        'name' => 'Is Domestic',
                        'method' => 'is_domestic',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'TransportInventory',
                'type' => 'inventory',
                'fields' => [
                    'travel_class' => [
                        'name' => 'Travel Class',
                        'method' => 'travelClass',
                    ],
                    'departs_at' => [
                        'name' => 'Departs At',
                        'method' => 'departs_at',
                    ],
                    'arrives_at' => [
                        'name' => 'Arrives At',
                        'method' => 'arrives_at',
                    ],
                    'departure_time_confirmed' => [
                        'name' => 'Departure Time Confirmed',
                        'method' => 'departure_time_confirmed',
                    ],
                    'arrival_time_confirmed' => [
                        'name' => 'Arrival Time Confirmed',
                        'method' => 'arrival_time_confirmed',
                    ],
                    'fit_selectable' => [
                        'name' => 'FIT Selectable',
                        'method' => 'fit_selectable',
                    ],
                    'total_stock' => [
                        'name' => 'Total Stock',
                        'method' => 'stock',
                    ],
                    'used_stock' => [
                        'name' => 'Used Stock',
                        'method' => 'used_stock',
                    ],
                    'purchase_price' => [
                        'name' => 'Purchase Price',
                        'method' => 'purchase_price',
                    ],
                    'sales_price' => [
                        'name' => 'Sales Price',
                        'method' => 'sales_price',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                    'tour_count' => [
                        'name' => 'Used on Tours',
                        'method' => 'used_on_tour_count',
                    ],
                ],
            ],
            2 => [
                'class' => 'TransportInventoryTour',
                'type' => 'tour',
                'fields' => [
                    'tour_sales_price' => [
                        'name' => 'Tour Sales Price',
                        'method' => 'tour_sales_price',
                    ],
                    'tour_component_type' => [
                        'name' => 'Tour Component Type',
                        'method' => 'tour_component_type',
                    ],
                    'tour_name' => [
                        'name' => 'Tour Name',
                        'method' => 'tour_name'
                    ],
                ]
            ],
        ];
    }

    public static function getCustomerFields(): array
    {
        return [
            0 => [
                'class' => 'Customer',
                'type' => 'customer',
                'fields' => [
                    'email' => [
                        'name' => 'Email',
                        'method' => 'email_address',
                    ],
                    'full_name' => [
                        'name' => 'Full Name',
                        'method' => 'full_name',
                    ],
                    'title' => [
                        'name' => 'Title',
                        'method' => 'title',
                    ],
                    'first_name' => [
                        'name' => 'First Name',
                        'method' => 'first_name',
                    ],
                    'middle_names' => [
                        'name' => 'Middle Names',
                        'method' => 'middle_names',
                    ],
                    'last_name' => [
                        'name' => 'Last Name',
                        'method' => 'last_name',
                    ],
                    'date_of_birth' => [
                        'name' => 'Date of Birth',
                        'method' => 'date_of_birth',
                    ],
                    'mobile_number' => [
                        'name' => 'Mobile Number',
                        'method' => 'mobile_number',
                    ],
                    'other_phone_number' => [
                        'name' => 'Other Phone Number',
                        'method' => 'other_phone_number',
                    ],
                    'home_address' => [
                        'name' => 'Home Address',
                        'method' => 'homeAddress',
                    ],
                    'billing_address' => [
                        'name' => 'Billing Address',
                        'method' => 'billingAddress',
                    ],
                    'emergency_contact_name' => [
                        'name' => 'Emergency Contact Name',
                        'method' => 'emergency_contact_name',
                    ],
                    'emergency_contact_relationship' => [
                        'name' => 'Emergency Contact Relationship',
                        'method' => 'emergency_contact_relationship',
                    ],
                    'emergency_contact_telephone' => [
                        'name' => 'Emergency Contact Telephone',
                        'method' => 'emergency_contact_telephone',
                    ],
                    'passport_first_name' => [
                        'name' => 'Passport First Name',
                        'method' => 'passport_first_name',
                    ],
                    'passport_middle_name' => [
                        'name' => 'Passport Middle Name',
                        'method' => 'passport_middle_name',
                    ],
                    'passport_last_name' => [
                        'name' => 'Passport Last Name',
                        'method' => 'passport_last_name',
                    ],
                    'passport_number' => [
                        'name' => 'Passport Number',
                        'method' => 'passport_number',
                    ],
                    'passport_expiry_date' => [
                        'name' => 'Passport Expiry Date',
                        'method' => 'passport_expiry_date',
                    ],
                    'passport_country_of_issue' => [
                        'name' => 'Passport Country of Issue',
                        'method' => 'passport_country_of_issue',
                    ],
                    'loyalty_number' => [
                        'name' => 'Loyalty Number',
                        'method' => 'loyalty_number',
                    ],
                    'profile_picture' => [
                        'name' => 'Profile Picture',
                        'method' => 'profile_picture',
                    ],
                    't_shirt_size' => [
                        'name' => 'T-Shirt Size',
                        'method' => 'tShirtSize',
                    ],
                    'hat_size' => [
                        'name' => 'Hat Size',
                        'method' => 'hatSize',
                    ],
                    'notes' => [
                        'name' => 'Notes',
                        'method' => 'notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'OrderCustomer',
                'type' => 'ordercustomer',
                'fields' => [
                    'tour_cost' => [
                        'name' => 'Tour Cost',
                        'method' => 'tour_cost'
                    ],
                    'single_occupancy_surcharge' => [
                        'name' => 'Single Occupancy Surcharge',
                        'method' => 'single_occupancy_surcharge'
                    ],
                    'travel_insurer' => [
                        'name' => 'Travel Insurer',
                        'method' => 'travel_insurer'
                    ],
                    'policy_number' => [
                        'name' => 'Policy Number',
                        'method' => 'policy_number'
                    ],
                    'is_lead_booker' => [
                        'name' => 'Is Lead Booker',
                        'method' => 'is_lead_booker'
                    ],
                    'booking_reference' => [
                        'name' => 'Booking Reference',
                        'method' => 'booking_reference'
                    ],
                    'ordered_on' => [
                        'name' => 'Ordered On',
                        'method' => 'ordered_on'
                    ],
                    'lead_booker_name' => [
                        'name' => 'Lead Booker Name',
                        'method' => 'lead_booker_name'
                    ],
                ]
            ],
            2 => [
                'class' => 'OrderComponent',
                'type' => 'ordercomponent',
                'fields' => [
                    'details' => [
                        'name' => 'Details',
                        'method' => 'details'
                    ],
                    'tour_sales_price' => [
                        'name' => 'Tour Sales Price',
                        'method' => 'tour_sales_price'
                    ],
                    'tour_component_type' => [
                        'name' => 'Tour Component Type',
                        'method' => 'tour_component_type'
                    ],
                ]
            ]
        ];
    }

    public static function getOrderInstallmentFields(): array
    {
        return [
            0 => self::getOrderFields(),
            1 => [
                'class' => 'OrderInstallment',
                'type' => 'orderinstallment',
                'fields' => [
                    'amount' => [
                        'name' => 'Amount',
                        'method' => 'amount',
                    ],
                    'due_on' => [
                        'name' => 'Due On',
                        'method' => 'due_on',
                    ],
                    'paid' => [
                        'name' => 'Paid',
                        'method' => 'paid',
                    ],
                ],
            ],
        ];
    }

    public static function getOrderPaymentFields(): array
    {
        return [
            0 => self::getOrderFields(),
            1 => [
                'class' => 'Payment',
                'type' => 'payment',
                'fields' => [
                    'payment_method' => [
                        'name' => 'Payment Method',
                        'method' => 'paymentMethod',
                    ],
                    'type' => [
                        'name' => 'Payment Type',
                        'method' => 'payment_type',
                    ],
                    'amount' => [
                        'name' => 'Amount',
                        'method' => 'amount',
                    ],
                    'paid_on' => [
                        'name' => 'Paid On',
                        'method' => 'paid_on',
                    ],
                ],
            ],
        ];
    }

    private static function getOrderFields(): array
    {
        return [
            'class' => 'Order',
            'type' => 'order',
            'fields' => [
                'booking_reference' => [
                    'name' => 'Booking Reference',
                    'method' => 'booking_reference',
                ],
                'ordered_on' => [
                    'name' => 'Ordered On',
                    'method' => 'ordered_on',
                ],
                'lead_booker_name' => [
                    'name' => 'Lead Booker Name',
                    'method' => 'lead_booker_name',
                ],
                'deposit' => [
                    'name' => 'Deposit',
                    'method' => 'deposit',
                ],
                'status' => [
                    'name' => 'Status',
                    'method' => 'status',
                ],
                'total' => [
                    'name' => 'Cost',
                    'method' => 'total',
                ],
                'order_paid' => [
                    'name' => 'Paid',
                    'method' => 'paid',
                ],
                'remaining' => [
                    'name' => 'Remaining',
                    'method' => 'remaining',
                ],
                'internal_notes' => [
                    'name' => 'Internal Notes',
                    'method' => 'internal_notes',
                ],
                'external_notes' => [
                    'name' => 'External Notes',
                    'method' => 'external_notes',
                ]
            ],
        ];
    }
}
