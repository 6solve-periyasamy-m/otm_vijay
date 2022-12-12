<?php

namespace App\Repository\Costing;

use App\Models\AdditionalCost;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;

class AdditionalCostRepository
{
    private AdditionalCost $cost;

    public function __construct(AdditionalCost $additionalCost)
    {
        $this->cost = $additionalCost;
    }

    public static function create(string $model, int $id, array $data): AdditionalCost|null
    {
        $parent = null;
        switch ($model) {
            case 'quote':
                $parent = Quote::find($id);
                break;
            case 'tour':
                $parent = Tour::find($id);
                break;
        }
        if ($parent == null) return null;
        $cost = AdditionalCost::make($data);
        $parent->costs()->save($cost);
        return $cost;
    }

    public function update(array $data): AdditionalCost
    {
        $this->cost->update($data);
        $this->cost->save();
        return $this->cost;
    }

    public function delete(): bool
    {
        return $this->cost->delete();
    }
}
