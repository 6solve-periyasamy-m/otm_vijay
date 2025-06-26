<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use Livewire\Component;

class ComponentsTable extends Component
{
    use LivewireForm, SendsEvents;

    public Quote $quote;

    protected $listeners = ['updatePaying'];

    public int $payingCount = 0;

    public function mount(Quote $quote, $payingCount = 0)
    {
        $this->quote = $quote;
        $this->payingCount = $payingCount;
    }

    public function updatePaying($payingCount)
    {
        $this->payingCount = $payingCount;
    }

    public function render()
    {
        return view('livewire.admin.quote.components-table', ['payingCount' => $this->payingCount]);
    }
}
