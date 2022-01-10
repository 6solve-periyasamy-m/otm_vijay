<?php

namespace App\Repository;

use App\Models\Report;
use Illuminate\Validation\Rule;

class BespokeReportRepository
{
    public static function getValidationRules(): array
    {
        return [
            'report_name' => 'required',
            'report_description' => 'required',
            'parent' => ['required', Rule::in(['accommodation','activity','flight','transport'])]
        ];
    }

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
                    $subData->description = $field['name'];
                    $subData->accessor = $field['method'];
                    $subData->type = $data['type'];
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
        foreach ($available as $field => $data) {
            if (in_array($field, $used)) {
                if ($lowestDepth < $data->depth) {
                    $lowestDepth = $data->depth;
                    $lowestClass = $data->class;
                    $lowestType = $data->type;
                }
            }
        }
        return ['depth' => $lowestDepth, 'class' => $lowestClass, 'type' => $lowestType,];
    }

    public static function getFieldsFromParent(string $parent): array
    {
        switch ($parent) {
            case 'accommodation':
                return self::getAccommodationFields();
            case 'activity':
                return self::getActivityFields();
            case 'flight':
                return self::getFlightFields();
            case 'transport':
                return self::getTransportFields();
            default:
                return [];
        }
    }

    public static function showReport(Report $report): array
    {
        $fields = self::convertFieldsToOutput(self::getFieldsFromParent($report->parent));
        $lowest = self::getLowestDepth($report->fields, $fields);
        $fields = self::convertFieldsToOutput(self::getFieldsFromParent($report->parent), $lowest['depth']);

        $output = [];
        $output['header'] = [];
        $output['data'] = [];
        foreach ($fields as $key => $data) {
            if (in_array($key, $report->fields)) {
                $output['header'][] = $data->description;
            }
        }
        foreach (app('\\App\\Models\\' . $lowest['class'])->all() as $row) {
            switch ($lowest['type']) {
                case 'component':
                    $output['data'][] = self::processComponent($row, $report->fields, $fields);
                    break;
                case 'inventory':
                    $output['data'][] = self::processInventory($row, $report->fields, $fields);
                    break;
                case 'tour':
                    $output['data'][] = self::processTourInventory($row, $report->fields, $fields);
                    break;
            }
        }
        return $output;

    }

    public static function processComponent($row, array $used, array $available): array
    {
        $data = [];
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'component') {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }

    public static function processInventory($row, array $used, array $available): array
    {
        $data = [];
        $component = $row->component;
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'component') {
                    $data[] = $component->{$info->accessor};
                }
                if ($info->type == 'inventory') {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }

    public static function processTourInventory($row, array $used, array $available): array
    {
        $data = [];
        $inventory = $row->inventory;
        $component = $inventory->component;
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'component') {
                    $data[] = $component->{$info->accessor};
                }
                if ($info->type == 'inventory') {
                    $data[] = $inventory->{$info->accessor};
                }
                if ($info->type == 'tour') {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }
}
