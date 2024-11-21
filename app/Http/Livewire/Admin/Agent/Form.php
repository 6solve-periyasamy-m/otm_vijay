<?php

namespace App\Http\Livewire\Admin\Agent;

use App\Models\Customer\Agent;
use LivewireUI\Modal\ModalComponent;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Form extends ModalComponent
{
    use SendsEvents;
    use LivewireForm;

    /**
     * @var Agent $agent
     */
    public $agent;
    public $organization;


    public function mount(Agent|int|null $agent = null): void
    {
        if (is_int($agent)) {
            $agent = Agent::find($agent);
        }
        if ($agent === null) {
            $agent = new Agent();
        }
        $this->agent = $agent;
    }

    public function save(): void
    {
        $this->validate();
        if (!isset($this->agent->organization_id)) {
            $this->agent->organization()->associate($this->organization);
        }
        $this->agent->save();
        $this->refreshTables();
        $this->closeModal();

        $this->redirect('/admin/organizations/' . $this->agent->organization_id);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.agent.form');
    }

    public function rules(): array
    {
        return [
            'agent.first_name' => 'required',
            'agent.last_name' => 'required',
            'agent.email' => 'nullable',
        ];
    }
}
