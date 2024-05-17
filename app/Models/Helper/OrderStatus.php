<?php

namespace App\Models\Helper;

use App\View\Components\Badge\Order as OrderBadge;
use Closure;
use Illuminate\Contracts\View\View;

enum OrderStatus: int
{

    case CANCELLED_NO_REFUND = -5;
    case CANCELLED_OVER_REFUNDED = -4;
    case CANCELLED_FULL_REFUND = -3;
    case CANCELLED_DEPOSIT_HELD = -2;
    case CANCELLED_REFUND_REQUIRED = -1;
    case PAID_IN_FULL = 0;
    case BALANCE_OUTSTANDING = 1;
    case PAYMENT_OVERDUE = 2;
    case OVERPAID = 3;
    case OCCUPANCY_NOT_SET = 4;
    case UNKNOWN = 999;

    public function description(): string
    {
        return $this->getStatusArray()['status'];
    }

    public function getStatusArray(): array
    {
        return match ($this) {
            self::CANCELLED_OVER_REFUNDED => ['status' => trans('custom.order.status.cancelled.over'), 'color' => 'secondary',],
            self::CANCELLED_FULL_REFUND => ['status' => trans('custom.order.status.cancelled.full'), 'color' => 'secondary',],
            self::CANCELLED_DEPOSIT_HELD => ['status' => trans('custom.order.status.cancelled.deposit'), 'color' => 'secondary',],
            self::CANCELLED_REFUND_REQUIRED => ['status' => trans('custom.order.status.cancelled.required'), 'color' => 'secondary',],
            self::PAID_IN_FULL => ['status' => trans('custom.order.status.full'), 'color' => 'success'],
            self::BALANCE_OUTSTANDING => ['status' => trans('custom.order.status.outstanding'), 'color' => 'warning'],
            self::PAYMENT_OVERDUE => ['status' => trans('custom.order.status.overdue'), 'color' => 'danger'],
            self::OVERPAID => ['status' => trans('custom.order.status.overpaid'), 'color' => 'info'],
            self::OCCUPANCY_NOT_SET => ['status' => trans('custom.order.status.occupancy'), 'color' => 'dark'],
            self::UNKNOWN => ['status' => 'Status Unknown', 'color' => 'dark'],
            self::CANCELLED_NO_REFUND => ['status' => trans('custom.order.status.cancelled.none'), 'color' => 'secondary',],
        };
    }

    public function color(): string
    {
        return $this->getStatusArray()['color'];
    }

    public static function asArray(): array
    {
        $array = [];
        foreach (OrderStatus::cases() as $case) {
            $array[$case->value] = $case->description();
        }
        return $array;
    }

    public static function asFilter(): array
    {
        $data = [];
        foreach (OrderStatus::cases() as $case) {
            $data[] = ['id' => $case->value, 'name' => $case->description()];
        }
        return $data;
    }

    public function badge(): View|Closure|string
    {
        return (new OrderBadge($this))->render();
    }
}
