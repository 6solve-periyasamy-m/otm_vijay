<?php

namespace App\Http\Livewire\Admin\System\Brand;

use App\Models\System\Brand;
use Livewire\Component;

class Card extends Component
{
    public Brand $brand;

    protected $listeners = ['brandUpdated' => 'update',];

    public function delete()
    {
        $this->brand->delete();
        $this->emit('brandDeleted', $this->brand);
    }

    public function update(Brand $brand)
    {
        if ($this->brand->id === null) return;
        if ($brand->id === $this->brand->id) {
            $this->brand = $brand;
        }
    }

    public function render()
    {
        return view('livewire.admin.system.brand.card');
    }
}
