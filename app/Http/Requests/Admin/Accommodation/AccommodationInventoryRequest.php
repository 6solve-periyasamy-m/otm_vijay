<?php

namespace App\Http\Requests\Admin\Accommodation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $room_type_id
 * @property int $board_type_id
 * @property int|null $stock_parent_id
 * @property string $check_in
 * @property string $check_out
 * @property bool $check_in_time_confirmed
 * @property bool $check_out_time_confirmed
 * @property bool $fit_selectable
 * @property int|null $stock
 * @property float|null $purchase_price
 * @property float|null $sales_price
 * @property string|null $internal_notes
 * @property string|null $external_notes
 */
class AccommodationInventoryRequest extends FormRequest
{
    public function getData(): array
    {
        return [
            'room_type_id' => $this->room_type_id,
            'board_type_id' => $this->board_type_id,
            'stock_parent_id' => $this->stock_parent_id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'check_in_time_confirmed' => $this->check_in_time_confirmed == 'on',
            'check_out_time_confirmed' => $this->check_out_time_confirmed == 'on',
            'fit_selectable' => $this->fit_selectable == 'on',
            'stock' => $this->stock ?? 0,
            'purchase_price' => $this->purchase_price ?? 0,
            'sales_price' => $this->sales_price ?? 0,
            'internal_notes' => $this->internal_notes,
            'external_notes' => $this->external_notes,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_type_id' => 'required|exists:room_types,id',
            'board_type_id' => 'required|exists:board_types,id',
            'stock_parent_id' => 'nullable|exists:accommodation_inventories,id',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date',
            'stock' => 'nullable|required_without:stock_parent_id|integer|gte:0',
            'purchase_price' => 'nullable|numeric|gte:0',
            'sales_price' => 'nullable|numeric|gte:0',
        ];
    }
}
