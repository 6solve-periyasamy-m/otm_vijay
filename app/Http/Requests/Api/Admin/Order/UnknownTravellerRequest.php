<?php

namespace App\Http\Requests\Api\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int|null $count
 */
class UnknownTravellerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'count' => 'nullable|integer',
        ];
    }
}
