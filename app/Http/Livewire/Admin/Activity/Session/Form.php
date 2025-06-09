<?php

namespace App\Http\Livewire\Admin\Activity\Session;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Activity\Session;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public Session|int|null $session;

    public function mount(Session|int|null $session = null)
    {
        $this->session = Session::getForMount($session);
    }

    public function save(): void
    {
        $this->validate();
        $this->session->save();
        $this->toast('Session saved successfully!', 'Successfully saved session!', 'success');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.activity.session.form');
    }

    public function rules()
    {
        return [
            'session.name' => 'required|min:3',
            'session.description' => 'nullable',
        ];
    }
}
