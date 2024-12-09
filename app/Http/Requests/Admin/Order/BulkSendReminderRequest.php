<?php

namespace App\Http\Requests\Admin\Order;

use App\Models\Order\Order;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $__api_token
 * @property array $orders An array of order ids
 */
class BulkSendReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('read', Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'orders.*' => ['required', 'exists:orders,id'],
        ];
    }
}
