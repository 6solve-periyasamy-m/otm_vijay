<?php

namespace App\Http\Livewire\Admin\Event\Content;

use Livewire\Component;
use App\Models\Helper\Enum\EventContentType;

class Section extends Component
{
    public int $eventId;
    public int $type;

    protected $listeners = ['eventContentUpdated' => '$refresh'];

    /**
     * Enum accessor (never public)
     */
    public function getTypeEnumProperty(): EventContentType
    {
        return EventContentType::from($this->type);
    }

    public function add(): void
    {
        $this->emit(
            'openModal',
            'admin.event.content.form',
            [
                'eventId' => $this->eventId,
                'type'    => $this->type,
            ]
        );
    }

    public function render()
    {
        return view('livewire.admin.event.content.section');
    }
}
