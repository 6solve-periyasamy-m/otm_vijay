<?php

namespace App\Http\Livewire\Admin\Quote\Section\Type;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\QuoteSectionType;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public QuoteSectionType|int|null $type = null;

    public function mount(QuoteSectionType|int|null $type = null): void
    {
        $this->type =  QuoteSectionType::getForMount($type);
    }

    public function save(): void
    {
        $this->validate();
        $this->type->save();
        $this->closeModal();
        $this->toast('Successfully Saved', 'Successfully saved Quote Section Type', 'success');
    }

    public function render()
    {
        return view('livewire.admin.quote.section.type.form');
    }

    public function rules(): array
    {
        return [
            'type.name' => 'required|string|unique:quote_section_types,name',
        ];
    }
}
