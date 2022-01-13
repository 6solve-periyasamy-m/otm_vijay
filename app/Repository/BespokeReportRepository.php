<?php

namespace App\Repository;

use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderFlight;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use App\Models\Report;
use Illuminate\Validation\Rule;

class BespokeReportRepository
{
    public static function getValidationRules(): array
    {
        return [
            'report_name' => 'required',
            'report_description' => 'required',
            'parent' => ['required', Rule::in(['accommodation', 'activity', 'flight', 'transport', 'customer', 'order-installment', 'payment'])]
        ];
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
        if ($lowest['type'] == 'order-component') {
            foreach (OrderAccommodation::all() as $row) {
                $output['data'][] = self::processOrderComponent($row, $report->fields, $fields);
            }
            foreach (OrderActivity::all() as $row) {
                $output['data'][] = self::processOrderComponent($row, $report->fields, $fields);
            }
            foreach (OrderFlight::all() as $row) {
                $output['data'][] = self::processOrderComponent($row, $report->fields, $fields);
            }
            foreach (OrderTransport::all() as $row) {
                $output['data'][] = self::processOrderComponent($row, $report->fields, $fields);
            }
            foreach (OrderMerchandise::all() as $row) {
                $output['data'][] = self::processOrderComponent($row, $report->fields, $fields);
            }
        } else {
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
                    case 'customer':
                        $output['data'][] = self::processCustomer($row, $report->fields, $fields);
                        break;
                    case 'order-customer':
                        $output['data'][] = self::processOrderCustomer($row, $report->fields, $fields);
                        break;
                    case 'order':
                        $output['data'][] = self::processOrder($row, $report->fields, $fields);
                        break;
                    case 'order-installment':
                        $output['data'][] = self::processOrderInstallment($row, $report->fields, $fields);
                        break;
                    case 'payment':
                        $output['data'][] = self::processOrderPayment($row, $report->fields, $fields);
                        break;
                    default:
                        break;
                }
            }
        }
        return $output;
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

    public static function getFieldsFromParent(string $parent): array
    {
        switch ($parent) {
            case 'accommodation':
                return ReportFieldRepository::getAccommodationFields();
            case 'activity':
                return ReportFieldRepository::getActivityFields();
            case 'flight':
                return ReportFieldRepository::getFlightFields();
            case 'transport':
                return ReportFieldRepository::getTransportFields();
            case 'customer':
                return ReportFieldRepository::getCustomerFields();
            case 'order-installment':
                return ReportFieldRepository::getOrderInstallmentFields();
            case 'payment':
                return ReportFieldRepository::getOrderPaymentFields();
            default:
                return [];
        }
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

    public static function processOrderComponent($row, array $used, array $available): array
    {
        $data = [];
        $orderCustomer = $row->orderCustomer;
        $customer = $orderCustomer->customer;
        $order = $orderCustomer->order;
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'customer') {
                    $data[] = $customer->{$info->accessor};
                }
                if ($info->type == 'order-customer') {
                    $data[] = $orderCustomer->{$info->accessor};
                }
                if ($info->type == 'order') {
                    $data[] = $order->{$info->accessor};
                }
                if ($info->type == 'order-component') {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
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

    public static function processCustomer($row, array $used, array $available): array
    {
        $data = [];
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'customer') {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }

    public static function processOrderCustomer($row, array $used, array $available): array
    {
        $data = [];
        $customer = $row->customer;
        $order = $row->order;
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'customer') {
                    $data[] = $customer->{$info->accessor};
                }
                if ($info->type == 'order-customer') {
                    $data[] = $row->{$info->accessor};
                }
                if ($info->type == 'order') {
                    $data[] = $order->{$info->accessor};
                }
            }
        }
        return $data;
    }

    public static function processOrder($row, array $used, array $available): array
    {
        $data = [];
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'order') {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }

    public static function processOrderInstallment($row, array $used, array $available): array
    {
        $data = [];
        $order = $row->order;
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'order-installment') {
                    $data[] = $row->{$info->accessor};
                }
                if ($info->type == 'order') {
                    $data[] = $order->{$info->accessor};
                }
            }
        }
        return $data;
    }

    public static function processOrderPayment($row, array $used, array $available): array
    {
        $data = [];
        $order = $row->order;
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->type == 'payment') {
                    $data[] = $row->{$info->accessor};
                }
                if ($info->type == 'order') {
                    $data[] = $order->{$info->accessor};
                }
            }
        }
        return $data;
    }
}
