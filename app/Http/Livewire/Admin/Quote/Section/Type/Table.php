<?php

namespace App\Http\Livewire\Admin\Quote\Section\Type;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\QuoteSectionType;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public function builder()
    {
        return QuoteSectionType::query()
            ->leftJoin('large_text_templates', 'large_text_templates.id', '=', 'quote_section_types.large_text_template_id');
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->sortable(),
            Column::raw('COALESCE(large_text_templates.name, "None")')
                ->label('Template')
                ->searchable()
                ->sortable(),
            NumberColumn::raw('(select COUNT(*) from quote_sections where quote_section_type_id = quote_section_types.id) AS related')
                ->label('Related')
                ->searchable()
                ->sortable()
                ->filterable(),
            ActionColumn::modal('type', 'admin.quote.section.type.form')
        ];
    }

    public function delete($id): void
    {
        $type = QuoteSectionType::find($id);
        if ($type === null) {
            $this->toast('Cannot Delete Type', 'Selected Quote Section Type Not Found.', 'danger');
            return;
        }
        if ($type->sections()->count() > 0) {
            $this->toast('Cannot Delete Type', 'Selected Quote Section Type Has Dependants', 'danger');
            return;
        }
        $type->delete();
        $this->toast('Type Deleted Successfully', 'Successfully deleted quote section type', 'success');
    }
}
