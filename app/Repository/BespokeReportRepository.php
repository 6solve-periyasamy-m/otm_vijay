<?php

namespace App\Repository;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\System\Report;
use Illuminate\Validation\Rule;
use StringFormatter;

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

    public static function showReport(Report $report, bool $format): array
    {
        $fields = ReportFieldRepository::convertFieldsToOutput(ReportFieldRepository::getFieldsFromParent($report->parent));
        $lowest = ReportFieldRepository::getLowestDepth($report->fields, $fields);
        $available = ReportFieldRepository::convertFieldsToOutput(ReportFieldRepository::getFieldsFromParent($report->parent), $lowest['depth']);

        $output = ['report' => $report,];
        $output['header'] = [];
        $output['data'] = [];
        foreach ($fields as $key => $data) {
            if (in_array($key, $report->fields)) {
                $output['header'][] = $data->description;
            }
        }
        if ($lowest['type'] == 'order-component') {
            $output['data'] = self::processOrderComponents($report->fields, $available, $format);
        } else {
            foreach (app('\\App\\Models\\' . $lowest['class'])->all() as $row) {
                switch ($lowest['type']) {
                    case 'order':
                    case 'customer':
                    case 'component':
                        $output['data'][] = self::process($row, $report->fields, $available, $format);
                        break;
                    case 'order-installment':
                    case 'payment':
                        $output['data'][] = self::processLower($row, 'order', $report->fields, $available, $format);
                        break;
                    case 'inventory':
                        $output['data'][] = self::processLower($row, 'component', $report->fields, $available, $format);
                        break;
                    case 'tour':
                        $output['data'][] = self::processLowest($row, 'component', 'inventory', $report->fields, $fields, $format);
                        break;
                    case 'order-customer':
                        $output['data'][] = self::processLower($row, 'customer', $report->fields, $fields, $format);
                        break;
                    default:
                        break;
                }
            }
        }
        return $output;
    }

    public static function processOrderComponents(array $used, array $available, bool $format = false): array
    {
        $data = self::processAccommodation($used, $available, $format);
        foreach (OrderActivity::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available, $format);
        }
        foreach (OrderFlight::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available, $format);
        }
        foreach (OrderTransport::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available, $format);
        }
        foreach (OrderMerchandise::all() as $row) {
            $data[] = self::processLowest($row, 'customer', 'orderCustomer', $used, $available, $format);
        }
        return $data;
    }

    private static function processAccommodation(array $used, array $available, bool $format): array
    {
        $rows = [];
        foreach (OrderAccommodation::all() as $row) {
            foreach ($row->group->orderCustomers as $objParent) {
                $objGrandparent = $objParent->customer;
                $data = [];
                foreach ($available as $key => $info) {
                    if (in_array($key, $used)) {
                        $field = 'Not Set';
                        if ($info->depth == 0) {
                            $field = $objGrandparent->{$info->accessor};
                        }
                        if ($info->depth == 1) {
                            $field = $objParent->{$info->accessor};
                        }
                        if ($info->depth == 2) {
                            $field = $row->{$info->accessor};
                        }
                        if ($format) {
                            $field = self::format($field, $info->format);
                        }
                        $data[] = $field;
                    }
                }
                $rows[] = $data;
            }
        }

        return $rows;
    }

    private static function format($data, string $format): ?string
    {
        if (!isset($data)) return 'Not Set';
        switch ($format) {
            case 'date':
                $data = StringFormatter::formatDate($data);
                break;
            case 'datetime':
                $data = StringFormatter::formatDateTime($data);
                break;
            case 'boolean':
                $data = StringFormatter::formatBoolean($data);
                break;
            case 'currency':
                $data = StringFormatter::formatCurrency($data);
                break;
            case 'asset':
                $data = asset($data);
                break;
            default:
                break;
        }
        return $data ?? 'Not Set';
    }

    private static function processLowest($row, $grandparent, $parent, array $used, array $available, bool $format = false): array
    {
        $data = [];
        $objParent = $row->{$parent};
        $objGrandparent = $objParent->{$grandparent};
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                $field = 'Not Set';
                if ($info->depth == 0) {
                    $field = $objGrandparent?->{$info->accessor};
                }
                if ($info->depth == 1) {
                    $field = $objParent?->{$info->accessor};
                }
                if ($info->depth == 2) {
                    $field = $row?->{$info->accessor};
                }
                if ($format) {
                    $field = self::format($field, $info->format);
                }
                $data[] = $field;
            }
        }
        return $data;
    }

    private static function process($row, array $used, array $available, bool $format = false): array
    {
        $data = [];
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                $field = $row->{$info->accessor};
                if ($format) {
                    $field = self::format($field, $info->format);
                }
                $data[] = $field;
            }
        }
        return $data;
    }

    private static function processLower($row, $parent, array $used, array $available, bool $format = false): array
    {
        $data = [];
        $objParent = $row->{$parent};
        foreach ($available as $key => $info) {
            if (in_array($key, $used)) {
                $field = 'Not Set';
                if ($info->depth == 0) {
                    $field = $objParent->{$info->accessor};
                }
                if ($info->depth == 1) {
                    $field = $row->{$info->accessor};
                }
                if ($format) {
                    $field = self::format($field, $info->format);
                }
                $data[] = $field;
            }
        }
        return $data;
    }
}
