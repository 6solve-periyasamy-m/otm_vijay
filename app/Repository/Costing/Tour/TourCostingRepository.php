<?php

namespace App\Repository\Costing\Tour;

use App\Models\Tour\Tour;
use App\Repository\Abstracts\CostingRepository;
use App\View\Components\Chart\Donut;
use App\View\Components\Chart\Line;
use Carbon\CarbonPeriod;
use Closure;
use DB;
use Illuminate\Contracts\View\View;

class TourCostingRepository extends CostingRepository
{
    private Tour $tour;

    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
    }

    public function getCostOfComponents(array $filter = ['Included', 'Add-on'], bool $merchandise = false): float
    {
        $cost = 0;
        foreach ($this->tour->repository->getComponents(false, true, true, true, $merchandise, $filter) as $component) {
            $cost += $component->getPurchasePrice();
        }
        foreach ($this->tour->templates as $template) {
            $cost += $template->inventory->purchase_price;
        }
        return $cost;
    }

    public function getMaxCostOfComponents(): float
    {
        $cost = 0;
        foreach ($this->tour->repository->getComponents(false, true, true, true, false, ['Included', 'Add-on']) as $component) {
            $max = null;
            foreach ($component->get()->upgrades as $upgrade) {
                if ($max === null || $max->tour_sales_price < $upgrade->upgrade->tour_sales_price) {
                    $max = $upgrade->upgrade;
                }
            }
            $cost += $max?->repository?->getPurchasePrice() ?? $component->getPurchasePrice();
        }
        foreach ($this->tour->templates as $template) {
            $max = null;
            foreach ($template->upgrades as $upgrade) {
                if ($max === null || $max->tour_sales_price < $upgrade->upgrade->tour_sales_price) {
                    $max = $upgrade->upgrade;
                }
            }
            $cost += $max?->repository?->getPurchasePrice() ?? $template->inventory->purchase_price;
        }
        return $cost;
    }

    public function getMaxCostToCustomer(): float
    {
        $cost = $this->tour->base_price_per_person;
        foreach ($this->tour->repository->getComponents(false, true, true, true, false, ['Included', 'Add-on']) as $component) {
            $max = null;
            foreach ($component->get()->upgrades as $upgrade) {
                if ($max === null || $max->tour_sales_price < $upgrade->upgrade->tour_sales_price) {
                    $max = $upgrade->upgrade;
                }
            }
            $cost += $max?->tour_sales_price ?? $component->get()->tour_sales_price;
        }
        foreach ($this->tour->templates as $template) {
            $max = null;
            foreach ($template->upgrades as $upgrade) {
                if ($max === null || $max->tour_sales_price < $upgrade->upgrade->tour_sales_price) {
                    $max = $upgrade->upgrade;
                }
            }
            $cost += $max?->tour_sales_price ?? $template->tour_sales_price;
        }
        return $cost;
    }

    public function getBaseMargin(): float
    {
        return ($this->tour->base_price_per_person / $this->getCostOfComponents(['Included'])) * 100;
    }

    public function getBaseProfit(): float
    {
        return $this->tour->base_price_per_person - $this->getCostOfComponents(['Included']);
    }
    
    public function getInstallmentData(): array
    {
        $data = [];
        foreach ($this->tour->orderInstallments as $orderInstallment) {
            $date = $orderInstallment->due_on->format('Y-m-d');
            if (!array_key_exists($date, $data)) {
                $row = collect();
                $row->date = $orderInstallment->due_on;
                $row->count = 0;
                $row->expected = 0;
                $row->received = 0;
                $data[$date] = $row;
            }
            $data[$date]->count = $data[$date]->count + 1;
            $data[$date]->expected = $data[$date]->expected + $orderInstallment->calculated_amount;
            $data[$date]->received = $data[$date]->received + $orderInstallment->repository->getAmountPaid();
        }
        ksort($data);
        return $data;
    }

    public function getTourRevenueDonut(): Closure|View|string
    {
        $potential = $this->tour->repository->getPotentialRevenue();
        $received = $this->tour->repository->getReceivedRevenue();
        $remaining = $this->tour->repository->getRemainingRevenue();
        $labels = ['Received', 'Remaining',];
        $values = [$received, $remaining,];
        $colors = ['#090', '#900',];
        if ($potential > -1) {
            $labels[] = 'Potential';
            $values[] = $potential-$remaining-$received;
            $colors[] = '#AAA';
        }
        return (new Donut($this->tour->name . ' Revenue', $labels, $values, $colors))->render();
    }

    public function getOrdersOverTime(): Closure|View|string|null
    {
        $query = DB::table('orders');
        $query->where('tour_id', '=', $this->tour->id);
        $query->groupBy(DB::raw('DATE(`ordered_on`)'));
        $query->orderBy(DB::raw('DATE(`ordered`)'));
        $query->select(DB::raw("DATE(`ordered_on`) as 'ordered'"), DB::raw("COUNT(`id`) as 'orders'"));
        $res = $query->get();
        $results = $res->mapWithKeys(fn($item, $key) => [$item->ordered => $item->orders])->toArray();
        $headers = [];
        $data = [];
        if ($res->first() === null) return null;
        foreach (CarbonPeriod::create($res->first()->ordered, $res->last()->ordered) as $date) {
            $headers[] = f_date($date);
            if (array_key_exists($date->format('Y-m-d'), $results)) {
                $data[] = $results[$date->format('Y-m-d')];
            } else {
                $data[] = 0;
            }
        }
        return (new Line('Orders over Time', $headers, $data))->render();
    }
}
