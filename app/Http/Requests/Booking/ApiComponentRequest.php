<?php

namespace App\Http\Requests\Booking;

/**
 * @property array{key: string, travellers: int}[] $components
 */
class ApiComponentRequest extends BookingOverviewRequest
{
    public function rules(): array
    {
        return [
            'components.*.component' => 'required|string',
            'components.*.travellers' => 'required|integer|min:0',
            ...parent::rules()
        ];
    }
}