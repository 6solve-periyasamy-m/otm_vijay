<?php

namespace App\Http\Livewire\Abstract;

use App\Http\Livewire\SendsEvents;
use App\Repository\Interfaces\InventoryContainerRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

abstract class StandaloneDatatable extends LivewireDatatable
{
    use SendsEvents;

    public int $quantity = 0;
    public int $cost = 0;

    public InventoryContainerRepository|null $linker = null;

    protected $listeners = ['quantityChanged' => 'updateQuantity', 'costChanged' => 'updateCost'];

    public abstract function getClass(): string;

    /**
     * @param Builder|DatabaseBuilder $query
     * @param string $idColumn
     * @return Builder
     */
    public function hideLinked(Builder|DatabaseBuilder $query, string $idColumn): Builder
    {
        return $query->whereNotExists(function ($query) use ($idColumn) {
            $query->select(DB::raw(1))
                ->from('supplier_contract_components')
                ->where('component_type', '=', $this->getClass())
                ->where('supplier_contract_id', '=', $this->linker->getId())
                ->whereColumn('supplier_contract_components.component_id', '=', $idColumn);
        });
    }

    /**
     * @param Builder|DatabaseBuilder $query
     * @param Carbon|string|null $from
     * @param Carbon|string|null $to
     * @param string $startColumn
     * @param string $endColumn
     * @return Builder|DatabaseBuilder
     */
    public function filter(Builder|DatabaseBuilder $query, Carbon|string|null $from, Carbon|string|null $to, string $startColumn = 'starts_at', string $endColumn = 'ends_at'): mixed
    {

        if (!empty($from)) {
            $query->where($startColumn, '>', is_string($from) ? Carbon::parse($from) : $from);
        }
        if (!empty($end)) {
            $query->where($endColumn, '<', is_string($to) ? Carbon::parse($to) : $to);
        }
        return $query;
    }

    public function updateQuantity($quantity): void
    {
        if (is_numeric($quantity)) {
            $this->quantity = intval($quantity);
        }
    }

    public function updateCost($cost): void
    {
        if (is_numeric($cost)) {
            $this->cost = floatval($cost);
        }
    }

    public function buildActions()
    {
        if ($this->linker === null) {
            return [];
        }
        return [
            Action::value('link')->label('Link')->callback(function ($mode, $items) {
                $this->linker->massAssociate($this->getClass(), $items, ['quantity' => $this->quantity, 'cost_per_unit' => $this->cost,]);
            })
        ];
    }
}