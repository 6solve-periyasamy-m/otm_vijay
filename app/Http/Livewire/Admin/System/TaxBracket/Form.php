<?php

namespace App\Http\Livewire\Admin\System\TaxBracket;

use App\Http\Livewire\SendsEvents;
use App\Models\System\TaxBracket;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /** @var TaxBracket $bracket Will be TaxBracket when used */
    public TaxBracket|int|null $bracket;

    public function mount(TaxBracket|int|null $bracket = null)
    {
        if (is_int($bracket)) {
            $bracket = TaxBracket::find($bracket);
        }
        if ($bracket === null) {
            $bracket = new TaxBracket();
        }
        $this->bracket = $bracket;
    }

    public function save()
    {
        $this->bracket->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.system.tax-bracket.form');
    }

    public function rules()
    {
        return [
            'bracket.name' => 'required',
            'bracket.description' => 'nullable',
            'bracket.rate' => 'nullable|numeric|lt:100|gt:0'
        ];
    }
}
