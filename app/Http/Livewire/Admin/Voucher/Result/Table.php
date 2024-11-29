<?php

namespace App\Http\Livewire\Admin\Voucher\Result;

use App\Http\Livewire\SendsEvents;
use App\Models\Voucher\Executors\VoucherExecutor;
use App\Models\Voucher\ResultType;
use App\Models\Voucher\VoucherCodeResult;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'voucher-result-table';

    use SendsEvents;

    public $voucher;
    public $showSearch = false;

    public function builder()
    {
        $query = VoucherCodeResult::query();
        if (!empty($this->voucher)) {
            $query = $query->where('voucher_code_id', '=', $this->voucher);
        }
        return $query;
    }

    public function delete($id)
    {
        $result = VoucherCodeResult::find($id);
        if ($result !== null) {
            $result->delete();
            $this->toastFromLang('voucher.result.success.deleted', 'success');
        } else {
            $this->toastFromLang('voucher.result.error.not-found', 'danger');
        }
        $this->refreshTables();
    }

    public function columns()
    {
        return [
            Column::callback('result_type', function ($type) { return ResultType::from($type)->description(); })
                ->label('Type'),
            Column::callback(['data', 'result_type'], function ($data, $type) { return VoucherExecutor::getExample($type, json_decode($data, true)); })
                ->label('Example'),
            Column::callback(['id'], function ($id) {
                return view('partials.admin.voucher.result.actions', [
                    'id' => $id,
                ]);
            })
                ->width('6rem')
                ->label('Actions')
        ];
    }
}
