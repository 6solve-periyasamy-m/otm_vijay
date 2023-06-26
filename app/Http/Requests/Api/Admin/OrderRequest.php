<?php

namespace App\Http\Requests\Api\Admin;

use App\Models\Order\Order;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $order
 */
class OrderRequest extends FormRequest
{
    public function getOrder(): Order
    {
        return Order::find($this->order);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'order' => 'required|integer|exists:orders,id'
        ];
    }
}
