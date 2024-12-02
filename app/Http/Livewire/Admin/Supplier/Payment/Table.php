<?php

namespace App\Http\Livewire\Admin\Supplier\Payment;

use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPaymentInformation;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'supplier-payment-table';

    public Supplier $supplier;

    public function builder()
    {
        return SupplierPaymentInformation::query()
            ->join('banks', 'banks.id', '=', 'supplier_payment_information.bank_id')
            ->where('supplier_id', '=', $this->supplier->id);
    }

    public function columns()
    {
        return [
            Column::name('banks.name')
                ->label('Bank')
                ->searchable()
                ->sortable(),
            Column::name('account_number')
                ->label('Account Number')
                ->searchable()
                ->sortable(),
            Column::name('sort_code')
                ->label('Sort Code')
                ->searchable()
                ->sortable(),
            Column::name('bic_swift_code')
                ->label('Swift Code')
                ->searchable()
                ->sortable(),
            Column::name('iban')
                ->label('IBAN')
                ->searchable()
                ->sortable(),
            Column::name('notes')
                ->label('Notes')
                ->searchable()
                ->sortable(),
            Column::callback(['id', 'supplier_id'], function ($id, $supplier) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'information',
                    'modal' => 'admin.supplier.payment.form',
                    'parent' => "'supplier': $supplier"
                ]);
            })
                ->label('Actions')
                ->unsortable()
                ->width('12rem'),

        ];
    }
}
