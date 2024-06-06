<?php

namespace App\Http\Livewire\Admin\Accommodation\RoomCategory;

use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\RoomCategory;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public function builder()
    {
        return RoomCategory::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->editable()
                ->sortable(),
            NumberColumn::raw('(SELECT count(*) FROM accommodation_inventories WHERE room_category_id = room_categories.id AND deleted_at IS NULL)')
                ->label('Related')
                ->sortable(),
            Column::callback(['id'], function ($id) {
                return '<button wire:click="delete(' . $id . ')" class="btn btn-outline-danger btn-sm mb-1" title="Delete">' . \Icon::delete() .'</button>';
            })
                ->label('Actions')
        ];
    }

    public function delete($id): void
    {
        /** @var RoomCategory|null $category */
        $category = RoomCategory::find($id);
        if ($category !== null) {
            if ($category->inventories()->count() > 0) {
                $this->toast('Cannot Delete Room Category', 'Cannot delete a room category with related inventories.', 'danger');
            } else {
                $category->delete();
                $this->refreshTables();
            }
        }
    }
}