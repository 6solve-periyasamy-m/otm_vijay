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
        $fields = ReportFieldRepository::convertFieldsToOutput(ReportFieldRepository::getFieldsFromParent($report->parent));
        $lowest = ReportFieldRepository::getLowestDepth($report->fields, $fields);
        $available = ReportFieldRepository::convertFieldsToOutput(ReportFieldRepository::getFieldsFromParent($report->parent), $lowest['depth']);

        $output = [];
        $output['header'] = [];
        $output['data'] = [];
        foreach ($fields as $key => $data) {
            if (in_array($key, $report->fields)) {
                $output['header'][] = $data->description;
            }
        }
        if ($lowest['type'] == 'order-component') {
            $output['data'] = self::processOrderComponents($report->fields, $available);
        } else {
            foreach (app('\\App\\Models\\' . $lowest['class'])->all() as $row) {
                switch ($lowest['type']) {
                    case 'order':
                    case 'customer':
                    case 'component':
                        $output['data'][] = self::process($row, $report->fields, $available);
                        break;
                    case 'order-installment':
                    case 'payment':
                        $output['data'][] = self::processLower($row, 'order', $report->fields, $available);
                        break;
                    case 'inventory':
                        $output['data'][] = self::processLower($row, 'component', $report->fields, $available);
                        break;
                    case 'tour':
                        $output['data'][] = self::processLowest($row, 'component', 'inventory', $report->fields, $fields);
                        break;
                    case 'order-customer':
                        $output['data'][] = self::processLower($row, 'customer', $report->fields, $fields);
                        break;
                    default:
                        break;
                }
            }
        }
        return $output;
    }

    public static function processOrderComponents(array $used, array $available): array
    {
        $data = [];
        foreach (OrderAccommodation::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available);
        }
        foreach (OrderActivity::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available);
        }
        foreach (OrderFlight::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available);
        }
        foreach (OrderTransport::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available);
        }
        foreach (OrderMerchandise::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available);
        }
        return $data;
    }

    private static function process($row, array $used, array $available): array
    {
        $data = [];
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                $data[] = $row->{$info->accessor};
            }
        }
        return $data;
    }

    private static function processLower($row, $parent, array $used, array $available): array
    {
        $data = [];
        $objParent = $row->{$parent};
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->depth == 0) {
                    $data[] = $objParent->{$info->accessor};
                }
                if ($info->depth == 1) {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }

    private static function processLowest($row, $grandparent, $parent, array $used, array $available): array
    {
        $data = [];
        $objParent = $row->{$parent};
        $objGrandparent = $objParent->{$grandparent};
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                if ($info->depth == 0) {
                    $data[] = $objGrandparent->{$info->accessor};
                }
                if ($info->depth == 1) {
                    $data[] = $objParent->{$info->accessor};
                }
                if ($info->depth == 2) {
                    $data[] = $row->{$info->accessor};
                }
            }
        }
        return $data;
    }
}
