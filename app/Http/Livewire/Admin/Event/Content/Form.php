<?php

namespace App\Http\Livewire\Admin\Event\Content;

use LivewireUI\Modal\ModalComponent;
use App\Models\Tour\EventContent;
use App\Models\Helper\Enum\EventContentType;

class Form extends ModalComponent
{
    public EventContent $content;
    public int $eventId;
    public int $type;

    public function mount(int $eventId, int $type, ?int $contentId = null)
    {
        $this->eventId = $eventId;
        $this->type = $type;

        if ($contentId) {
            $this->content = EventContent::where('id', $contentId)
                ->where('event_id', $eventId)
                ->firstOrFail();
        } else {
            $this->content = new EventContent();
            $this->content->event_id = $eventId;
            $this->content->type     = EventContentType::from($type);
            $this->content->active   = true;
        }
    }

    public function inputChanged($name, $value = null)
    {
        data_set($this, $name, $value);
    }

    protected function rules()
    {
        return [
            'content.question' => 'required|string|max:255',
            'content.answer'   => 'required|string',
            'content.active'   => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();
        $this->content->event_id = $this->eventId;
        $this->content->type     = EventContentType::from($this->type);
        $this->content->save();

        $this->emit('refreshLivewireDatatable');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.event.content.form');
    }
}
