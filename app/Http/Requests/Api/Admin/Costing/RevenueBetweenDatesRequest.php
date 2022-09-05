<?php

namespace App\Http\Requests\Api\Admin\Costing;

use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $from
 * @property string|null $to
 * @property string|null $period
 */
class RevenueBetweenDatesRequest extends FormRequest
{
    public function getFromDate(): ?Carbon
    {
        try {
            return Carbon::createFromFormat('Y-m-d', $this->from);
        } catch (Exception) { return null; }
    }

    public function getToDate(): ?Carbon
    {
        try {
            if (isset($this->to)) {
                return Carbon::createFromFormat('Y-m-d', $this->to);
            } else {
                return $this->getFromDate()?->addDays($this->period);
            }
        } catch (Exception) { return null; }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from' => 'required|date|date_format:Y-m-d',
            'to' => 'nullable|date|date_format:Y-m-d',
            'period' => 'required_without:to|integer'
        ];
    }
}
