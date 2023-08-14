<?php

namespace App\Http\Livewire\Admin\Voucher;

use App\Models\Voucher\VoucherCode;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return VoucherCode::query();
    }

    public function columns()
    {
        return [
            Column::callback(['code', 'id'], function ($code, $id) {
                $route = route('vouchers.view', ['voucher' => $id]);
                return "<a href=\"{$route}\">$code</a>";
            })
                ->label('Code')
                ->sortable()
                ->searchable(),
            Column::name('name')
                ->sortable()
                ->searchable()
                ->editable(),
            Column::name('description')
                ->sortable()
                ->searchable()
                ->editable(),
            BooleanColumn::raw('IF(NOW() < expiry AND active = 1, 1, 0)')
                ->label('Active')
                ->filterable()
                ->sortable(),
            DateColumn::name('expiry')
                ->sortable(),
            NumberColumn::name('results.id:count')
                ->label('Results Count')
                ->sortable(),

            Column::callback(['id'], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'voucher',
                    'modal' => 'admin.voucher.form',
                    'route' => 'vouchers.view'
                ]);
            })
                ->label('Actions')
                ->unsortable()
                ->width('12rem'),
        ];
    }

    public function destroy($voucher)
    {
        /** @var VoucherCode $voucher */
        $voucher = VoucherCode::find($voucher);
        $voucher?->repository->delete();
    }
}
