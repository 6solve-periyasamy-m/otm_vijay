<?php

namespace App\Http\Livewire\Admin\Customer\MerchandiseCategory;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Customer\MerchandiseCategory;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public MerchandiseCategory|int|null $category;

    public function mount(MerchandiseCategory|int|null $category = null)
    {
        $this->category = MerchandiseCategory::getForMount($category);
    }

    public function save()
    {
        $this->validate();
        $this->category->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.customer.merchandise-category.form');
    }

    public function rules()
    {
        return [
            'category.name' => 'required|string',
        ];
    }
}
