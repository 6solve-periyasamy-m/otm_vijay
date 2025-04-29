<?php

namespace App\Http\Livewire\Admin\Activity\Seating;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Activity\Seating;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public Seating|int|null $seating;

    public function mount(Seating|int|null $seating = null)
    {
        $this->seating = Seating::getForMount($seating);
    }

    public function save(): void
    {
        $this->validate();
        $this->seating->save();
        $this->toast('Seating saved successfully!', 'Successfully saved seating!', 'success');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.activity.seating.form');
    }

    public function rules()
    {
        return [
            'seating.name' => 'required|min:3',
            'seating.description' => 'nullable',
        ];
    }
}
