<?php

namespace App\Http\Requests\Admin\Tour;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $name
 * @property string|null $description
 * @property UploadedFile $image
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property string|null $booking_url
 * @property int $event_category
 * @property int|null $tax_bracket_id
 * @property int|null $parent_id
 * @property int|null $brand_id
 * @property string|null $notes
 */
class EventRequest extends FormRequest
{
    public function getData(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'booking_url' => $this->booking_url,
            'event_category' => $this->event_category,
            'tax_bracket_id' => $this->tax_bracket_id,
            'parent_event_id' => $this->parent_id,
            'brand_id' => $this->brand_id,
            'notes' => $this->notes,
        ];
    }

    public function prepareForValidation(): void
    {
        if ($this->brand_id <= 0) {
            $this->merge([
                'brand_id' => null,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'booking_url' => 'nullable',
            'event_category' => 'required|integer',
            'tax_bracket_id' => 'nullable|integer|exists:tax_brackets,id',
            'parent_id' => 'nullable|integer|exists:events,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'notes' => 'nullable|string',
        ];
    }
}
