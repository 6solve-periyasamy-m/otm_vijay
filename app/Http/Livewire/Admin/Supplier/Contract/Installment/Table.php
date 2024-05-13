<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Installment;

use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractInstallment;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractInstallment::query()
                ->join('supplier_contracts', 'supplier_contracts.id', '=', 'supplier_contract_installments.supplier_contract_id')
                ->where('supplier_contract_id', '=', $this->contract->id)
                ->orderBy('due');
    }

    public function columns()
    {
        $totalColumn = NumberColumn::raw("SUM(`amount`) OVER(ORDER BY `due`) AS total")
            ->label('Total Owed');
        $totalColumn->callback = function ($amount) { return f_currency($amount, $this->contract->currency->code); };
        $paidColumn = BooleanColumn::raw("SUM(`amount`) OVER(ORDER BY `due`) AS paid_total")
            ->label('Paid');
        $paidColumn->callback = function ($amount) { return f_bool($amount <= $this->contract->payments()->sum('amount')); };
        return [
            DateColumn::name('due'),
            NumberColumn::callback(['amount', 'supplier_contracts.agreed_exchange'], function ($amount, $ex) { return f_currency(sigfig($amount / $ex)); })
                ->label('Local Amount Owed'),
            NumberColumn::callback('amount', function ($amount) { return f_currency($amount, $this->contract->currency->code); })
                ->label('Amount Owed'),
            $totalColumn,
            $paidColumn,
            Column::callback(['id', 'supplier_contract_id'],  function ($id, $contract) {
                return view('partials.admin.livewire.table.actions', [
                    'modal' => 'admin.supplier.contract.installment.form',
                    'field' => 'installment',
                    'id' => $id,
                    'parent' => "'contract': $contract",
                ]);
            })
                ->label(__('custom.table.actions')),
        ];
    }

    public function delete($id)
    {
        SupplierContractInstallment::find($id)?->delete();
        $this->refreshLivewireDatatable();
    }
}