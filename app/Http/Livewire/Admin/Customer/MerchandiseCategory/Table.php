<?php

namespace App\Http\Livewire\Admin\Customer\MerchandiseCategory;

use App\Http\Livewire\SendsEvents;
use App\Models\Customer\MerchandiseCategory;
use Icon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use App\Http\Livewire\Abstract\ActionColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "merchandise-categories-table";

    public function builder()
    {
        return MerchandiseCategory::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->editable()
                ->sortable(),
            NumberColumn::raw('(SELECT count(*) FROM customer_merchandises WHERE merchandise_category_id = merchandise_categories.id)')
                ->label('Related')
                ->sortable(),
            ActionColumn::modal('category', 'admin.customer.merchandise-category.form')
        ];
    }

    public function delete($id): void
    {
        $category = MerchandiseCategory::find($id);
        if ($category === null) {
            $this->toast('Unable to Delete', 'Cannot find requested category to delete', 'danger');
            return;
        }
        
        if ($category->merchandises()->count() > 0) {
            $this->toast('Unable to Delete', 'This category is assigned to merchandise and cannot be deleted.', 'danger');
            return;
        }        
        $category->delete();
        $this->toast('Success', 'category deleted successfully.', 'success');
    }
}
