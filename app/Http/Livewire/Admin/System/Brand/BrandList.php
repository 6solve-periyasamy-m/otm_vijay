<?php

namespace App\Http\Livewire\Admin\System\Brand;

use App\Models\System\Brand;
use Illuminate\Support\Collection;
use Livewire\Component;

class BrandList extends Component
{

    public Collection $brands;
    protected $listeners = ['brandCreated' => 'addBrand',];

    public function mount()
    {
        $this->brands = Brand::all();
    }

    public function addBrand(Brand $brand)
    {
        $this->brands->add($brand);
    }

    public function render()
    {
        return view('livewire.admin.system.brand.list', ['system' => Brand::getSystemBrand(),]);
    }
}
