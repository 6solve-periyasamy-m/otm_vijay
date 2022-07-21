<?php

namespace App\Http\Requests\Admin\Merchandise;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $tour_component_type
 * @property float $tour_sales_price
 * @property string|null $is_bookable
 */
class MerchandiseInventoryTourRequest extends FormRequest
{
    public function getDataset(): array
    {
        return [
            'tour_component_type' => $this->tour_component_type,
            'tour_sales_price' => $this->tour_sales_price,
            'is_bookable' => $this->is_bookable == 'on',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tour_component_type' => ['required', Rule::in(['Included', 'Add-on']),],
            'tour_sales_price' => 'required|numeric|min:0',
        ];
    }
}
