<?php

namespace App\Repository\Reporting;

class ReportFieldRepository
{
    public static function getFieldsFromParent(string $parent): array
    {
        return match ($parent) {
            'accommodation' => ReportFieldRepository::getAccommodationFields(),
            'activity' => ReportFieldRepository::getActivityFields(),
            'flight' => ReportFieldRepository::getFlightFields(),
            'transport' => ReportFieldRepository::getTransportFields(),
            'customer' => ReportFieldRepository::getCustomerFields(),
            'order-installment' => ReportFieldRepository::getOrderInstallmentFields(),
            'payment' => ReportFieldRepository::getOrderPaymentFields(),
            default => [],
        };
    }

    public static function convertFieldsToOutput(array $fields, int $lowest = -1): array
    {
        $output = [];
        foreach ($fields as $depth => $data) {
            if ($lowest < 0 || $depth <= $lowest) {
                foreach ($data['fields'] as $key => $field) {
                    $subData = collect();
                    $subData->name = $key;
                    $subData->class = $data['class'];
                    $subData->depth = $depth;
                    $subData->eager = $data['eager'] ?? [];
                    $subData->description = $field['name'];
                    $subData->accessor = $field['method'];
                    $subData->type = $data['type'];
                    $subData->format = $field['format'] ?? 'string';
                    $output[$key] = $subData;
                }
            }
        }
        return $output;
    }

    public static function getLowestDepth(array $used, array $available): array
    {
        $lowestDepth = -1;
        $lowestClass = null;
        $lowestType = null;
        $lowestEager = null;
        foreach ($available as $field => $data) {
            if (in_array($field, $used) && $lowestDepth < $data->depth) {
                $lowestDepth = $data->depth;
                $lowestClass = $data->class;
                $lowestType = $data->type;
                $lowestEager = $data->eager;
            }
        }
        return ['depth' => $lowestDepth, 'class' => $lowestClass, 'type' => $lowestType, 'eager' => $lowestEager];
    }

    public static function getAccommodationFields(): array
    {
        return [
            0 => [
                'class' => 'Accommodation\Accommodation',
                'type' => 'component',
                'eager' => ['address',],
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
                        'format' => 'date',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                        'format' => 'asset',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'address' => [
                        'name' => 'Address',
                        'method' => 'address',
                    ],
                    'internal_notes' => [
                        'name' => 'Internal Notes',
                        'method' => 'internal_notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'Accommodation\AccommodationInventory',
                'type' => 'inventory',
                'eager' => ['accommodation', 'roomType', 'boardType', 'accommodation.address'],
                'fields' => array_merge([
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
                        'format' => 'datetime',
                    ],
                    'check_out' => [
                        'name' => 'Check Out',
                        'method' => 'check_out',
                        'format' => 'datetime',
                    ],
                    'check_in_time_confirmed' => [
                        'name' => 'Check In Time Confirmed',
                        'method' => 'check_in_time_confirmed',
                        'format' => 'boolean',
                    ],
                    'check_out_time_confirmed' => [
                        'name' => 'Check Out Time Confirmed',
                        'method' => 'check_out_time_confirmed',
                        'format' => 'boolean',
                    ],
                ], self::getInventoryFooter()),
            ],
            2 => self::getTourInventoryFooter('Accommodation\AccommodationInventoryTour', ['inventory', 'inventory.accommodation', 'inventory.roomType', 'inventory.boardType', 'inventory.accommodation.address', 'tour']),
        ];
    }

    public static function getActivityFields(): array
    {
        return [
            0 => [
                'class' => 'Activity\Activity',
                'type' => 'component',
                'eager' => ['address', 'activityType'],
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
                        'format' => 'asset',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'address' => [
                        'name' => 'Address',
                        'method' => 'address',
                    ],
                    'internal_notes' => [
                        'name' => 'Internal Notes',
                        'method' => 'internal_notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'Activity\ActivityInventory',
                'type' => 'inventory',
                'eager' => ['activity','activity.activityType','activity.address', 'ticketType'],
                'fields' => array_merge([
                    'ticket_type' => [
                        'name' => 'Ticket Type',
                        'method' => 'ticketType',
                    ],
                    'starts_at' => [
                        'name' => 'Starts At',
                        'method' => 'starts_at',
                        'format' => 'datetime',
                    ],
                    'ends_at' => [
                        'name' => 'Ends At',
                        'method' => 'ends_at',
                        'format' => 'datetime',
                    ],
                ], self::getInventoryFooter()),
            ],
            2 => self::getTourInventoryFooter('Activity\ActivityInventoryTour', ['inventory', 'inventory.ticketType', 'inventory.activity', 'inventory.activity.address', 'inventory.activity.activityType', 'tour'])
        ];
    }

    public static function getFlightFields(): array
    {
        return [
            0 => [
                'class' => 'Flight\Flight',
                'type' => 'component',
                'eager' => ['departureAirport', 'departureAirport.address', 'arrivalAirport', 'arrivalAirport.address', ],
                'fields' => [
                    'departure_airport' => [
                        'name' => 'Departure Airport',
                        'method' => 'departureAirport',
                    ],
                    'arrival_airport' => [
                        'name' => 'Arrival Airport',
                        'method' => 'arrivalAirport',
                    ],
                    'available_from' => [
                        'name' => 'Available From',
                        'method' => 'available_from',
                    ],
                    'is_domestic' => [
                        'name' => 'Is Domestic',
                        'method' => 'is_domestic',
                        'format' => 'boolean',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                        'format' => 'asset',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'internal_notes' => [
                        'name' => 'Internal Notes',
                        'method' => 'internal_notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'Flight\FlightInventory',
                'type' => 'inventory',
                'eager' => ['travelClass', 'flight.departureAirport', 'flight.departureAirport.address', 'flight.arrivalAirport', 'flight.arrivalAirport.address', ],
                'fields' => array_merge([
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
                        'format' => 'datetime',
                    ],
                    'starts_at' => [
                        'name' => 'Departs At',
                        'method' => 'departs_at',
                        'format' => 'datetime',
                    ],
                    'ends_at' => [
                        'name' => 'Arrives At',
                        'method' => 'arrives_at',
                        'format' => 'datetime',
                    ],
                ], self::getInventoryFooter()),
            ],
            2 => self::getTourInventoryFooter('Flight\FlightInventoryTour', ['inventory.travelClass', 'inventory.flight.departureAirport', 'inventory.flight.departureAirport.address', 'inventory.flight.arrivalAirport', 'inventory.flight.arrivalAirport.address', 'tour'],)
        ];
    }

    public static function getTransportFields(): array
    {
        return [
            0 => [
                'class' => 'Transport\Transport',
                'type' => 'component',
                'eager' => ['departureAddress', 'arrivalAddress', 'operator', 'transportType'],
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
                        'format' => 'boolean',
                    ],
                    'image_url' => [
                        'name' => 'Image URL',
                        'method' => 'image_url',
                        'format' => 'asset',
                    ],
                    'currency' => [
                        'name' => 'Currency',
                        'method' => 'currency',
                    ],
                    'internal_notes' => [
                        'name' => 'Internal Notes',
                        'method' => 'internal_notes',
                    ],
                ],
            ],
            1 => [
                'class' => 'Transport\TransportInventory',
                'type' => 'inventory',
                'eager' => ['travelClass', 'transport.departureAddress', 'transport.arrivalAddress', 'transport.operator', 'transport.transportType'],
                'fields' => array_merge([
                    'travel_class' => [
                        'name' => 'Travel Class',
                        'method' => 'travelClass',
                    ],
                    'ticket_number' => [
                        'name' => 'Ticket Number',
                        'method' => 'ticket_number',
                    ],
                    'departs_at' => [
                        'name' => 'Departs At',
                        'method' => 'departs_at',
                        'format' => 'datetime',
                    ],
                    'arrives_at' => [
                        'name' => 'Arrives At',
                        'method' => 'arrives_at',
                        'format' => 'datetime',
                    ],
                    'departure_time_confirmed' => [
                        'name' => 'Departure Time Confirmed',
                        'method' => 'departure_time_confirmed',
                        'format' => 'boolean',
                    ],
                    'arrival_time_confirmed' => [
                        'name' => 'Arrival Time Confirmed',
                        'method' => 'arrival_time_confirmed',
                        'format' => 'boolean',
                    ],
                ], self::getInventoryFooter()),
            ],
            2 => self::getTourInventoryFooter('Transport\TransportInventoryTour', ['inventory.travelClass', 'inventory.transport.departureAddress', 'inventory.transport.arrivalAddress', 'inventory.transport.operator', 'inventory.transport.transportType']),
        ];
    }

    public static function getCustomerFields(): array
    {
        return [
            0 => [
                'class' => 'Customer\Customer',
                'type' => 'customer',
                'eager' => ['homeAddress', 'billingAddress', 'tShirtSize', 'hatSize'],
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
                        'format' => 'date',
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
                        'format' => 'date',
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
                        'format' => 'asset',
                    ],
                    't_shirt_size' => [
                        'name' => 'T-Shirt Size',
                        'method' => 'tShirtSize',
                    ],
                    'hat_size' => [
                        'name' => 'Hat Size',
                        'method' => 'hatSize',
                    ],
                    'registered' => [
                        'name' => 'Has Account',
                        'method' => 'registered',
                        'format' => 'boolean',
                    ],
                    'internal_notes' => [
                        'name' => 'Internal Notes',
                        'method' => 'internal_notes',
                    ],
                    'external_notes' => [
                        'name' => 'External Notes',
                        'method' => 'external_notes',
                    ],
                    'dietary_notes' => [
                        'name' => 'Dietary Notes',
                        'method' => 'dietary_notes',
                    ],
                    'mobility_notes' => [
                        'name' => 'Mobility Notes',
                        'method' => 'mobility_notes',
                    ],
                    'created_at_system' => [
                        'name' => 'Created At',
                        'method' => 'created_at'
                    ]
                ],
            ],
            1 => [
                'class' => 'Order\OrderCustomer',
                'type' => 'order-customer',
                'eager' => ['order', 'order.tour', 'customer', 'customer.homeAddress', 'customer.billingAddress', 'customer.tShirtSize', 'customer.hatSize'],
                'fields' => [
                    'booking_reference' => [
                        'name' => 'Booking Reference',
                        'method' => 'booking_reference',
                    ],
                    'tour_name' => [
                        'name' => 'Tour Name',
                        'method' => 'tour_name',
                    ],
                    'ordered_on' => [
                        'name' => 'Ordered On',
                        'method' => 'ordered_on',
                        'format' => 'datetime',
                    ],
                    'lead_booker_name' => [
                        'name' => 'Lead Booker Name',
                        'method' => 'lead_booker_name',
                    ],
                    'tour_cost' => [
                        'name' => 'Tour Cost',
                        'method' => 'tour_cost',
                        'format' => 'currency',
                    ],
                    'single_occupancy_surcharge' => [
                        'name' => 'Single Occupancy Surcharge',
                        'method' => 'single_occupancy_surcharge',
                        'format' => 'currency',
                    ],
                    'has_surcharge' => [
                        'name' => 'Is Single Occupancy',
                        'method' => 'has_surcharge',
                        'format' => 'boolean',
                    ],
                    'travel_insurer' => [
                        'name' => 'Travel Insurer',
                        'method' => 'travel_insurer',
                    ],
                    'policy_number' => [
                        'name' => 'Policy Number',
                        'method' => 'policy_number',
                    ],
                    'is_lead_booker' => [
                        'name' => 'Is Lead Booker',
                        'method' => 'is_lead_booker',
                        'format' => 'boolean',
                    ],
                    'oc_internal_notes' => [
                        'name' => 'Order Customer Internal Notes',
                        'method' => 'internal_notes',
                    ],
                    'oc_external_notes' => [
                        'name' => 'Order Customer External Notes',
                        'method' => 'external_notes',
                    ],
                    'accommodation_notes' => [
                        'name' => 'Accommodation Notes',
                        'method' => 'accommodation_notes',
                    ],
                    'activity_notes' => [
                        'name' => 'Activity Notes',
                        'method' => 'activity_notes',
                    ],
                    'flight_notes' => [
                        'name' => 'Flight Notes',
                        'method' => 'flight_notes',
                    ],
                    'transport_notes' => [
                        'name' => 'Transport Notes',
                        'method' => 'transport_notes',
                    ],
                ]
            ],
            2 => [
                'class' => 'OrderComponent',
                'type' => 'order-component',
                'eager' => [],
                'fields' => [
                    'details' => [
                        'name' => 'Details',
                        'method' => 'details',
                    ],
                    'tour_sales_price' => [
                        'name' => 'Tour Sales Price',
                        'method' => 'tour_sales_price',
                        'format' => 'currency',
                    ],
                    'tour_component_type' => [
                        'name' => 'Tour Component Type',
                        'method' => 'tour_component_type',
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
                'class' => 'Order\OrderInstallment',
                'type' => 'order-installment',
                'eager' => ['order', 'order.leadBooker'],
                'fields' => [
                    'amount' => [
                        'name' => 'Amount',
                        'method' => 'calculated_amount',
                        'format' => 'currency',
                    ],
                    'due_on' => [
                        'name' => 'Due On',
                        'method' => 'due_on',
                        'format' => 'date',
                    ],
                    'paid' => [
                        'name' => 'Paid',
                        'method' => 'paid',
                        'format' => 'boolean',
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
                'class' => 'Order\Payment\Payment',
                'type' => 'payment',
                'eager' => ['order', 'order.leadBooker', 'paymentMethod',],
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
                        'format' => 'currency',
                    ],
                    'paid_on' => [
                        'name' => 'Paid On',
                        'method' => 'paid_on',
                        'format' => 'datetime',
                    ],
                ],
            ],
        ];
    }

    private static function getOrderFields(): array
    {
        return [
            'class' => 'Order\Order',
            'type' => 'order',
            'eager' => ['leadBooker'],
            'fields' => [
                'booking_reference' => [
                    'name' => 'Booking Reference',
                    'method' => 'booking_reference',
                ],
                'ordered_on' => [
                    'name' => 'Ordered On',
                    'method' => 'ordered_on',
                    'format' => 'datetime',
                ],
                'lead_booker_name' => [
                    'name' => 'Lead Booker Name',
                    'method' => 'lead_booker_name',
                ],
                'deposit' => [
                    'name' => 'Deposit',
                    'method' => 'deposit',
                    'format' => 'currency',
                ],
                'status' => [
                    'name' => 'Status',
                    'method' => 'status',
                ],
                'total' => [
                    'name' => 'Cost',
                    'method' => 'total',
                    'format' => 'currency',
                ],
                'order_paid' => [
                    'name' => 'Paid',
                    'method' => 'paid',
                    'format' => 'currency',
                ],
                'remaining' => [
                    'name' => 'Remaining',
                    'method' => 'remaining',
                    'format' => 'currency',
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

    private static function getInventoryFooter(): array
    {
        return [
            'fit_selectable' => [
                'name' => 'FIT Selectable',
                'method' => 'fit_selectable',
                'format' => 'boolean',
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
                'format' => 'currency',
            ],
            'sales_price' => [
                'name' => 'Sales Price',
                'method' => 'sales_price',
                'format' => 'currency',
            ],
            'notes' => [
                'name' => 'Notes',
                'method' => 'notes',
            ],
            'tour_count' => [
                'name' => 'Used on Tours',
                'method' => 'used_on_tour_count',
            ],
        ];
    }

    private static function getTourInventoryFooter(string $class, array $eager = []): array
    {
        return [
            'class' => $class,
            'type' => 'tour',
            'eager' => $eager,
            'fields' => [
                'tour_name' => [
                    'name' => 'Tour Name',
                    'method' => 'tour_name'
                ],
                'tour_sales_price' => [
                    'name' => 'Tour Sales Price',
                    'method' => 'tour_sales_price',
                    'format' => 'currency',
                ],
                'tour_component_type' => [
                    'name' => 'Tour Component Type',
                    'method' => 'tour_component_type',
                ],
                'used_tour_stock' => [
                    'name' => 'Tour Component Stock Sold',
                    'method' => 'used_tour_stock',
                ]
            ]
        ];
    }
}
