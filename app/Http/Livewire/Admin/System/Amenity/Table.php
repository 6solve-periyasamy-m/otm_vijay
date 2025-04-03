<?php

namespace App\Http\Livewire\Admin\System\Amenity;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Accommodation\Amenity;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "amenities-table";

    public function builder()
    {
        return Amenity::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
            Column::callback(['image_url'], function ($icon_url) {
                    if (empty($icon_url)) {
                        $image_url = '-Nil-';
                    } else {
                        $image_url = '<img src="' . asset($icon_url) . '" alt="Icon" width="42" height="42" />';
                    }
                    return $image_url;
                })
                ->label('Icon'),
            ActionColumn::modal('amenity', 'admin.system.amenity.form')
        ];
    }

    public function delete($id): void
    {
        $amenity = Amenity::find($id);
        if ($amenity === null) {
            $this->toast('Unable to Delete', 'Cannot find requested amenity to delete', 'danger');
            return;
        }
        
        if ($amenity->accommodations()->exists()) {
            $this->toast('Unable to Delete', 'This amenity is assigned to accommodations and cannot be deleted.', 'danger');
            return;
        }        
        $amenity->delete();
        $this->toast('Success', 'Amenity deleted successfully.', 'success');
    }
}
