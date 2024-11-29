<?php

namespace App\Http\Livewire\Admin\Activity;

use App\Http\Livewire\Abstract\AddressColumn;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityType;
use App\Models\Helper\Enum\ActivityCategory;
use App\Models\Location\Country;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = "activity-table";

    public function builder()
    {
        return Activity::query()
            ->leftJoin('activity_types', 'activity_types.id', '=', 'activities.activity_type_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'activities.address_id')
            ->leftJoin('countries', 'countries.id', '=', 'addresses.country_id');
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::callback(['activity_category'], static function ($category) { return ActivityCategory::from($category)->label(); })
                ->label('Category')
                ->sortable()
                ->searchable()
                ->filterable(ActivityCategory::asFilter()),
            Column::name('activity_types.name')
                ->label('Type')
                ->sortable()
                ->searchable()
                ->filterable(ActivityType::pluck('name')),
            AddressColumn::table('addresses', 'countries')
                ->label('Address')
                ->sortable()
                ->searchable(),
            Column::callback(['description'], static function ($description) { return $description; })
                ->label('Description')
                ->sortable()
                ->searchable(),
            Column::callback(['id'], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'activity',
                    'edit' => 'activities.edit',
                    'route' => 'activities.view'
                ]);
            })
                ->label(__('custom.table.actions'))
                ->width('15rem')
                ->unsortable(),
        ];
    }
}
