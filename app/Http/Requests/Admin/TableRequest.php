<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property bool|null $historic
 * @property bool|null $hideNoCategory
 * @property int|null $activityFilter
 */
class TableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [];
    }
}
