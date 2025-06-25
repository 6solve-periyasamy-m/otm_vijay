<?php

namespace App\Http\Livewire\Admin\System\Faq;

use App\Models\System\Faq;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use App\Models\System\Brand;

class Table extends LivewireDatatable
{
    public $name = "payment-method-table";

    public function builder()
    {        
        return Faq::query()
            ->leftJoin('brands', 'faqs.brand_id', '=', 'brands.id')
            ->select('faqs.*', 'brands.name as brand_name');
    }

    public function columns()
    {
        $defaultBrandName = Brand::getSystemBrand()->name ?? 'Default Brand';
        return [
            Column::callback(['brand_id'], function ($brandId) use ($defaultBrandName) {
                $brand = Brand::find($brandId);
                return $brand ? $brand->name : $defaultBrandName;
            })->label('Brand'),

            Column::name('question')
                ->label('Question')
                ->searchable(),

            Column::name('answer')
                ->label('Answer')
                ->searchable(),

            Column::callback(['active'], function ($is_active) {
                return $is_active ? 'Yes' : 'No';
            })->label('Active'),

            Column::callback(['id',], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'faq',
                    'modal' => 'admin.system.faq.form',
                ]);
            })
                ->label(__('custom.table.actions'))
                ->unsortable(),
        ];
    }
}
