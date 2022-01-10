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
                return ReportFieldRepository::getAccommodationFields();
            case 'activity':
                return ReportFieldRepository::getActivityFields();
            case 'flight':
                return ReportFieldRepository::getFlightFields();
            case 'transport':
                return ReportFieldRepository::getTransportFields();
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
