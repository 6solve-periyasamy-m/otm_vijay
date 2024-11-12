<?php

namespace App\Http\Livewire\Admin\Event;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Helper\Enum\EventType;
use App\Models\Tour\Event;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireForm, WithFileUploads;

    public Event|int|null $event;
    public UploadedFile|null $image = null;

    public function mount(Event|int|null $event = null)
    {
        $this->event = Event::getForMount($event);
        $this->event->event_category = $this->event->event_category ?? EventType::NORMAL;
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        $this->render();
    }

    public function save()
    {
        $this->validate();
        if ($this->image !== null) {
            $this->event->image_url = store_file($this->image, $this->event->image_url);
        }
        $this->event->save();
        return redirect()->route('events.view', ['event' => $this->event]);
    }

    public function render()
    {
        return view('livewire.admin.event.form');
    }

    protected function rules(): array
    {
        return [
            'event.name' => 'required|string',
            'event.description' => 'nullable|string',
            'event.starts_at' => 'required|date|date_format:Y-m-d',
            'event.ends_at' => 'required|date|date_format:Y-m-d',
            'event.tax_bracket_id' => 'nullable|integer|exists:tax_brackets,id',
            'event.brand_id' => 'nullable|integer|exists:brands,id',
            'event.parent_event_id' => 'nullable|integer|exists:events,id',
            'event.event_category' => ['required', Rule::enum(EventType::class)],
            'event.notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
            'event.booking_url' => 'nullable|string',
        ];
    }
}
