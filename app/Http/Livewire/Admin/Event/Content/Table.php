<?php

namespace App\Http\Livewire\Admin\Event\Content;

use App\Models\Tour\EventContent;
use App\Models\Helper\Enum\EventContentType;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public int $eventId;
    public int $type;

    protected $listeners = ['toggleActive' => 'toggleActive'];

    public function builder()
    {
        return EventContent::query()
            ->where('event_id', $this->eventId)
            ->where('type', EventContentType::from($this->type));
    }

    public function columns()
    {
        return [
            Column::name('question')
                ->label('Question')
                ->searchable(),

            Column::callback(['id', 'active'], function ($id, $active) {
                return view('partials.admin.livewire.table.toggle-active', [
                    'id' => $id,
                    'is_active' => $active,
                ]);
            })->label('Active'),

            Column::callback(['id'], function ($id) {
                return view('partials.admin.livewire.table.event-content-actions', [
                    'id'      => $id,
                    'eventId' => $this->eventId,
                    'type'    => $this->type,
                ]);
            })
            ->unsortable()
            ->label('Actions'),
        ];
    }

    public function toggleActive($id)
    {
        if ($content = EventContent::find($id)) {
            $content->update(['active' => ! $content->active]);
            $this->emit('refreshLivewireDatatable');
        }
    }
}
