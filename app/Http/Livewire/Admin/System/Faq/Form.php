<?php

namespace App\Http\Livewire\Admin\System\Faq;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\System\Faq;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;
    use LivewireForm;

    public $faq;

    public function mount(Faq|int|null $faq = null)
    {
        if (is_int($faq)) {
            $faq = Faq::find($faq);
        }
        $this->faq = $faq ?? new Faq();
    }
    public function save(): void
    {
        $this->validate();
        $this->faq->save();
        $this->refreshTables();
        $this->closeModal();
    }
    public function render()
    {
        return view('livewire.admin.system.faq.form');
    }
    public function rules()
    {
        return [
            'faq.question' => 'required',
            'faq.answer' => 'required',
            'faq.active' => 'required',
            'faq.brand_id' => 'nullable|int',
        ];
    }
}
