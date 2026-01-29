<?php

namespace App\Http\Livewire\Admin\Event\Content;

use LivewireUI\Modal\ModalComponent;
use App\Models\Tour\EventContent;
use App\Models\Helper\Enum\EventContentType;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class Form extends ModalComponent
{
    use WithFileUploads;
    public EventContent $content;
    public int $eventId;
    public int $type;
    public UploadedFile|string|null $icon = null;

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
        $this->icon = null;
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
            'icon'             => 'nullable|file|mimes:svg,jpg,jpeg,png|max:2048',
            'content.active'   => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->icon !== null) {
            $this->content->icon = store_file($this->icon, $this->content->icon);
        }
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
