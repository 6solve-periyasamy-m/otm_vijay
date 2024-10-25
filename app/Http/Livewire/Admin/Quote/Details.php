<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use Livewire\Component;

class Details extends Component
{
    use LivewireForm, SendsEvents;

    public Quote $quote;

    public function inputChanged(?string $key = null)
    {
        $this->save();
    }

    public function save()
    {
        $success = $this->quote->save();
        if ($success) {
            $this->toast('Quote Saved Successfully', 'Successfully saved the changes you have made.', 'success');
        } else {
            $this->toast('Quote Saving Failed', 'Failed to save the changes you have made. Please refresh and try again.', 'danger');
        }
    }

    public function render()
    {
        return view('livewire.admin.quote.details');
    }

    public function rules()
    {
        return [
            'quote.name' => 'required|string|min:3',
        ];
    }
}
