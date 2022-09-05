<?php

namespace App\Http\Requests\Api\Admin\Costing;

use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array $data
 */
class RevenueBetweenDatesSetRequest extends FormRequest
{
    public function getRequestedDates(): array
    {
        $dates = [];
        foreach ($this->data as $datum) {
            $from = Carbon::createFromFormat('Y-m-d', $datum['from']);
            if (array_key_exists('to', $datum)) {
                $to = Carbon::createFromFormat('Y-m-d', $datum['from']);
            } elseif (array_key_exists('period', $datum)) {
                $to = $from->clone()->addDays($datum['period']);
            } else {
                $to = $from->clone()->addMonth()->subDay();
            }
            $dates[] = ['from' => $from, 'to' => $to,];
        }
        return $dates;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data.*.from' => 'required|date|date_format:Y-m-d',
            'data.*.to' => 'nullable|date|date_format:Y-m-d',
            'data.*.period' => 'nullable|integer'
        ];
    }
}
