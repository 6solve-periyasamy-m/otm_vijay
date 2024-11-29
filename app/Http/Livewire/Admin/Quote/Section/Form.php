<?php

namespace App\Http\Livewire\Admin\Quote\Section;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteSection;
use App\Models\System\LargeTextTemplate;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireForm, WithFileUploads, SendsEvents;

    public Quote|int $quote;
    public QuoteSection|int|null $section = null;
    public UploadedFile|string|null $image = null;
    public int|null $bodyTemplate = null;

    public function mount(Quote|int $quote, QuoteSection|int|null $section = null)
    {
        $this->quote = Quote::getForMount($quote);
        $this->section = QuoteSection::getForMount($section);
        $this->section->quote_id = $this->quote->id;
        $this->section->order = $this->section->order ?? 0;
        $this->section->hidden = $this->section->hidden ?? false;
    }

    public function updated($key, $value): void
    {
        $this->validateOnly($key);
        if ($key === 'bodyTemplate') {
            $template = LargeTextTemplate::find($this->bodyTemplate);
            if ($template !== null) {
                $this->section->body = $template->content;
                $this->updateValue('section.body', $template->content);
            }
        }
        $this->render();
    }

    public function save(): void
    {
        $this->validate();
        $this->section->quote_id = $this->section->quote_id ?? $this->quote->id;
        $this->section->order = $this->section->order ?? 0;
        $this->section->hidden = $this->section->hidden ?? false;
        if ($this->image !== null) {
            $this->section->image_url = store_file($this->image, $this->section->image_url);
        }
        $this->section->save();
        $this->redirect(route('quotes.view', ['quote' => $this->quote,]));
    }

    public function render()
    {
        return view('livewire.admin.quote.section.form');
    }

    public function rules(): array
    {
        return [
            'section.title' => 'required|string|max:255',
            'section.quote_section_type_id' => 'nullable|integer|exists:quote_section_types,id',
            'section.body' => 'required|string',
            'section.order' => 'nullable|integer',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'section.hidden' => 'nullable|boolean',
            'section.currency_id' => 'nullable|integer|exists:currencies,id',
            'section.purchase_price' => 'nullable|numeric|gte:0',
            'section.quantity' => 'nullable|integer|gte:0',
        ];
    }
}
