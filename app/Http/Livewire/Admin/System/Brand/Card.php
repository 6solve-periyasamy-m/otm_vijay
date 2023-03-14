<?php

namespace App\Http\Livewire\Admin\System\Brand;

use App\Models\System\Brand;
use Livewire\Component;

class Card extends Component
{
    public Brand $brand;

    public function render()
    {
        return view('livewire.admin.system.brand.card');
    }
}
