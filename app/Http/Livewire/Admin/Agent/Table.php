<?php

namespace App\Http\Livewire\Admin\Agent;

use App\Models\Customer\Agent;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return Agent::query()
            ->leftJoin('organizations', 'organization_id', '=', 'organizations.id')
            ->leftJoin('orders', 'orders.organization_id',  '=', 'organizations.id')
            ->leftJoin('quotes', 'quotes.organization_id', '=', 'organizations.id')
            ->groupBy('organizations.id');
    }

    public function columns()
    {
        return [
            Column::callback(['first_name', 'last_name', 'id'], function ($first_name, $last_name, $id) {
                return '<a href="' . route('agents.view', ['agent' => $id,]) .'">' . $first_name . ' ' .  $last_name .  '</a>';
            })
                ->label(__('agent.table.columns.first_name') . ' ' . __('agent.table.columns.last_name'))
                ->searchable()
                ->sortable(),
            Column::name('email')
                ->label(__('agent.table.columns.email'))
                ->searchable()
                ->sortable(),
            NumberColumn::raw('COUNT(orders.id)')
                ->label(__('organization.table.columns.orders'))
                ->sortable(),
            NumberColumn::raw('COUNT(quotes.id)')
                ->label(__('organization.table.columns.quotes'))
                ->sortable(),
            Column::callback(['id'], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'agent',
                    'modal' => 'admin.agent.form',
                    'route' => 'agents.view'
                ]);
            })
                ->label(__('custom.table.actions'))
                ->unsortable(),
        ];
    }
}