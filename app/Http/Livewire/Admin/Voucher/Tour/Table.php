<?php

namespace App\Http\Livewire\Admin\Voucher\Tour;

use App\Http\Livewire\SendsEvents;
use App\Models\Voucher\VoucherCode;
use App\Models\Voucher\VoucherTour;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = "voucher-tour-table";

    use SendsEvents;

    public $voucher;
    public $showSearch = false;

    public function builder()
    {
        return VoucherTour::query()
            ->where('voucher_code_id', '=', $this->voucher)
            ->leftJoin('tours', 'tours.id', '=', 'voucher_tours.tour_id')
            ->leftJoin('events', 'events.id', '=', 'tours.event_id');
    }

    public function invert($id)
    {
        $link = VoucherTour::find($id);
        $link->invert = !$link->invert;
        $link->save();
        $this->refreshTables();
    }

    public function unlink($id)
    {
        $voucher = VoucherCode::find($this->voucher);
        $voucher->detach($id);
        $this->refreshTables();
    }

    public function columns()
    {
        return [
            Column::name('tours.name')
                ->label(__('voucher.tour.table.name')),

            Column::callback(['events.name'], function ($name) {
                return empty($name) ? 'No Event' : $name;
            })
                ->label(__('voucher.tour.table.event')),

            BooleanColumn::raw('IF(invert = 1, 0, 1)')
                ->label(__('voucher.tour.table.included')),

            Column::callback(['id', 'tours.id', 'invert'], function ($id, $tourId, $invert) {
                return view('partials.admin.voucher.tour.actions', [
                    'id' => $id,
                    'tour' => $id,
                    'invert' => $invert == 1,
                ]);
            })
                ->label('Actions')
                ->unsortable()
                ->width('9rem'),
        ];
    }
}
