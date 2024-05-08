<?php

namespace App\Http\Requests\Admin\Quote;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $due
 * @property float $amount
 * @property string $percentage
 */
class QuoteInstallmentRequest extends FormRequest
{

    public function getDueDate(): Carbon
    {
        return Carbon::parse($this->due);
    }

    public function getData(): array
    {
        return ['due_on' => $this->due, 'amount' => $this->amount, 'percentage' => $this->percentage == 'on'];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'due' => 'required|date',
            'amount' => 'required|numeric|min:0.01'
        ];
    }
}
