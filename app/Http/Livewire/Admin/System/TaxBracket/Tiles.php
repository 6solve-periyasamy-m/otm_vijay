<?php

namespace App\Http\Livewire\Admin\System\TaxBracket;

use App\Http\Livewire\SendsEvents;
use App\Models\System\TaxBracket;
use Livewire\Component;
use Settings;

class Tiles extends Component
{
    use SendsEvents;
    public TaxBracket $nullBracket;

    public function mount()
    {
        $this->nullBracket = $this->getNullBracket();
    }

    public function updateTaxBracket(int|null $id = null): void
    {
        if ($id === null || TaxBracket::find($id) !== null) {
            Settings::set('system.tax.bracket', $id);
            $this->toastFromLang('custom.tax.toast.success', 'success');
        } else {
            $this->toastFromLang('custom.tax.toast.invalid', 'danger');
        }
        $this->refreshTables();
    }

    private function getNullBracket(): TaxBracket
    {
        return new TaxBracket([
            'id' => null,
            'name'=> __('custom.tax.null.name'),
            'description'=> __('custom.tax.null.description'),
            'rate' => null,
            ]);
    }

    public function render()
    {
        return view('livewire.admin.system.tax-bracket.tiles');
    }
}
