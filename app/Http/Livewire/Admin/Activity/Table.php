<?php

namespace App\Http\Livewire\Admin\Activity;

use App\Actions\Activity\DeleteActivity;
use App\Exceptions\CannotDeleteException;
use App\Http\Livewire\Abstract\AddressColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityType;
use App\Models\Helper\Enum\ActivityCategory;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "activity-table";
    public bool $archived = false;

    public function builder()
    {
        $query = Activity::query()
            ->leftJoin('activity_types', 'activity_types.id', '=', 'activities.activity_type_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'activities.address_id')
            ->leftJoin('countries', 'countries.id', '=', 'addresses.country_id');
        if (!$this->archived) {
            $query = $query->where('archived', '=', false);
        }
        return $query;
    }

    public function columns()
    {
        $archiveColumn = BooleanColumn::name('archived')->label('Archived')->filterable();
        $this->archived || $archiveColumn->hide();

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
            $archiveColumn,
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

    public function delete($id): void
    {
        $activity = Activity::find($id);
        if ($activity === null) {
            $this->toast('Cannot Delete Activity', 'The requested activity was not found.', 'danger');
        }
        if (! auth()->user()->can('self-child-access', [$activity, \App\Models\Activity\ActivityInventory::class])) {
            $this->toast('Unauthorized', 'You do not have permission to delete this activity.', 'danger');
            return;
        }
        try {
            DeleteActivity::run(Activity::find($id));
            $this->toast('Activity Deleted Successfully', 'Successfully deleted the requested activity.', 'success');
        } catch (CannotDeleteException $e) {
            $this->toast('Cannot Delete Activity', $e->getMessage(), 'danger');
        }
    }
}
