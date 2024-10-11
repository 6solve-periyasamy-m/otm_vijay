<?php

namespace App\Http\Requests\Admin\Tour\Category;

use App\View\Helper\DisplayModeColor;
use App\View\Helper\DisplayModeType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $name
 * @property string $display_mode_type
 * @property string $display_mode_color
 */
class TourCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'display_mode_type' => ['required', Rule::in(DisplayModeType::keys())],
            'display_mode_color' => ['required', Rule::in(DisplayModeColor::keys())],
        ];
    }
}
