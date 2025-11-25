<?php

namespace App\Http\Livewire\Admin\Customer\AirlineFrequentFlyers;

use App\Http\Livewire\SendsEvents;
use App\Models\Customer\AirlineFrequentFlyers;
use Icon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use App\Http\Livewire\Abstract\ActionColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "airline-frequent-flyers-table";

    public function builder()
    {
        return AirlineFrequentFlyers::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->editable()
                ->sortable(),
            NumberColumn::raw('(SELECT count(*) FROM customers WHERE airline_frequent_flyers.id = customers.airline_frequent_flyers_id)')
                ->label('Related')
                ->sortable(),
            ActionColumn::modal('frequentflyer', 'admin.customer.airline-frequent-flyers.form')
        ];
    }

    public function delete($id): void
    {
        $frequentFlyer = AirlineFrequentFlyers::find($id);
        if ($frequentFlyer === null) {
            $this->toast('Unable to Delete', 'Cannot find requested flyer to delete', 'danger');
            return;
        }        
        if ($frequentFlyer->customers()->exists()) {
            $this->toast('Unable to Delete', 'This flyer is assigned to customer and cannot be deleted.', 'danger');
            return;
        }        
        $frequentFlyer->delete();
        $this->toast('Success', 'flyer deleted successfully.', 'success');
    }
}
