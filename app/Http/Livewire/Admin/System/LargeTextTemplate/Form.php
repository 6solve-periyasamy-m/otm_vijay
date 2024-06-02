<?php

namespace App\Http\Livewire\Admin\System\LargeTextTemplate;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\System\LargeTextTemplate;
use Livewire\Component;

class Form extends Component
{
    use SendsEvents;
    use LivewireForm;

    /** @var LargeTextTemplate $template */
    public LargeTextTemplate|int|null $template;

    public function mount(LargeTextTemplate|int|null $template = null)
    {
        if (is_int($template)) { $template = LargeTextTemplate::find($template); }
        if ($template === null) { $template = new LargeTextTemplate(); }
        $this->template = $template;
    }

    public function save(): void
    {
        $this->validate();
        $this->template->save();
        $this->redirect(route('settings.template'));
    }

    public function render()
    {
        return view('livewire.admin.system.large-text-template.form');
    }

    public function rules()
    {
        return [
            'template.name' => 'required',
            'template.description' => 'nullable',
            'template.type' => 'required|integer',
            'template.content' => 'nullable'
        ];
    }
}
