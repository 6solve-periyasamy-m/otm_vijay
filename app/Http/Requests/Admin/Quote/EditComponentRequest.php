<?php

namespace App\Http\Requests\Admin\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $sales_price
 * @property string $shown
 * @property int|null $quantity
 * @property int|null $order
 */
class EditComponentRequest extends FormRequest
{
    public function getData(): array
    {
        return [
            'tour_sales_price' => $this->sales_price,
            'price_shown' => $this->shown == 'on',
            'quantity' => $this->quantity,
            'document_order' => $this->order,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sales_price' => 'required|numeric|min:0',
            'quantity' => 'nullable|numeric|gte:0',
            'order' => 'nullable|numeric',
        ];
    }
}
