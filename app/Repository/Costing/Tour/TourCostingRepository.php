<?php

namespace App\Repository\Costing\Tour;

use App\Models\Tour\Tour;
use App\Repository\Abstracts\CostingRepository;

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
}
