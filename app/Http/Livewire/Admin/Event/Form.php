<?php

namespace App\Http\Livewire\Admin\Event;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Helper\Enum\EventType;
use App\Models\Helper\Enum\LargeTextType;
use App\Models\System\LargeTextTemplate;
use App\Models\Tour\Event;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireForm, WithFileUploads, SendsEvents;

    public Event|int|null $event;
    public $termsTemplate;
    public $user;
    public UploadedFile|string|null $image = null;
    public UploadedFile|string|null $banner = null;

    public function mount(Event|int|null $event = null)
    {
        $this->event = Event::getForMount($event);
        if ($event === null) {
            $terms = LargeTextTemplate::where('default', '=', true)->where('type', '=', LargeTextType::TERMS)->first();
            $this->event->final_terms = $terms?->content;
            $this->termsTemplate = $terms?->id;
        }
        $this->event->event_category = $this->event->event_category ?? EventType::NORMAL;
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        if ($key === 'termsTemplate') {
            $this->refreshTermsTemplate();
        }
        if ($key === 'user') {
            $this->refreshUser();
        }
        $this->render();
    }

    private function refreshTermsTemplate(): void
    {
        $template = LargeTextTemplate::find($this->termsTemplate);
        if ($template !== null) {
            $this->event->final_terms = $template->content;
            $this->updateValue('event.final_terms', $template->content);
        }
    }

    private function refreshUser(): void
    {
        $user = User::find($this->user);
        if ($user !== null) {
            $this->event->onsite_name = $user->name;
            $this->event->onsite_email = $user->email;
            $this->event->onsite_phone = $user->phone ?? null;
        }
    }

    public function save()
    {
        $this->validate();
        if ($this->image !== null) {
            $this->event->image_url = store_file($this->image, $this->event->image_url);
        }
        if ($this->banner !== null) {
            $this->event->banner_url = store_file($this->banner, $this->event->banner_url);
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
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
            'event.booking_url' => 'nullable|string',
            'event.onsite_name' => 'nullable|string',
            'event.onsite_email' => 'nullable|string',
            'event.onsite_phone' => 'nullable|string',
            'event.final_terms' => 'nullable|string',
            'event.itinerary_email_subject' => 'nullable|string',
            'event.itinerary_email_template' => 'nullable|string',
        ];
    }
}
