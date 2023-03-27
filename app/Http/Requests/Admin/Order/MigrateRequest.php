<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $tour_id
 * @property string|null $reset_adjustments
 * @property string|null $reset_prices
 */
class MigrateRequest extends FormRequest
{
    public function resetAdjustments(): bool
    {
        return $this->reset_adjustments == 'on';
    }

    public function resetPrices(): bool
    {
        return $this->reset_prices == 'on';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tour_id' => 'required|exists:tours,id',
        ];
    }
}
