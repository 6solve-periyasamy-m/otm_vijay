<?php

namespace App\Http\Livewire\Admin\Transport\Occupancy;

use App\Http\Livewire\SendsEvents;
use App\Models\Transport\TransportOccupancy;
use Icon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use App\Http\Livewire\Abstract\ActionColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "transport-occupancies-table";

    public function builder()
    {
        return TransportOccupancy::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->editable()
                ->sortable(),
            NumberColumn::raw('(SELECT count(*) FROM transport_inventories WHERE transport_occupancy_id = transport_occupancies.id AND deleted_at IS NULL)')
                ->label('Related')
                ->sortable(),
            // Column::callback(['id'], function ($id) {
            //     return '<button wire:click="delete(' . $id . ')" class="btn btn-outline-danger btn-sm mb-1" title="Delete">' . Icon::delete() .'</button>';
            // })
            //     ->label('Actions'),
            ActionColumn::modal('occupancy', 'admin.transport.occupancy.form')
        ];
    }

    public function delete($id): void
    {

        $occupancy = TransportOccupancy::find($id);
        if ($occupancy === null) {
            $this->toast('Unable to Delete', 'Cannot find requested occupancy to delete', 'danger');
            return;
        }
        
        if ($occupancy->inventories()->count() > 0) {
            $this->toast('Unable to Delete', 'This occupancy is assigned to inventory and cannot be deleted.', 'danger');
            return;
        }        
        $occupancy->delete();
        $this->toast('Success', 'occupancy deleted successfully.', 'success');
    }
}
