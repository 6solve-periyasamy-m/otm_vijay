<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Tour\Tour;
use Livewire\Component;

class Details extends Component
{
    use LivewireForm;

    public Tour $tour;

    public function updated($key, $value)
    {
        $this->validateOnly($key);
        if ($key === 'tour.name') {
            $this->tour->name = $value;
            $this->tour->save();
        }
    }

    public function render()
    {
        return view('livewire.admin.tour.details');
    }

    public function rules(): array
    {
        return [
            'tour.name' => ['required', 'string', 'min:2'],
        ];
    }
}
