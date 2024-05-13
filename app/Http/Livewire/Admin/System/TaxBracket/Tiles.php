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

    public $listeners = ['refreshLivewireDatatable' => 'render',];

    public function updateTaxBracket(int|null $id = null): void
    {
        if ($id === null || TaxBracket::find($id) !== null) {
            Settings::set('system.tax.bracket', $id);
            $this->toastFromLang('custom.tax.toast.success', 'success', true);
        } else {
            $this->toastFromLang('custom.tax.toast.invalid', 'danger', true);
        }
        $this->refreshTables();
    }

    public function delete($id)
    {
        TaxBracket::find($id)?->delete();
        $this->refreshTables();
    }

    public function render()
    {
        $this->nullBracket = Settings::getNullTaxBracket();
        return view('livewire.admin.system.tax-bracket.tiles');
    }
}
