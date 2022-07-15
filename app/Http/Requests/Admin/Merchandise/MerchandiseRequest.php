<?php

namespace App\Http\Requests\Admin\Merchandise;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $name
 * @property int $type
 * @property UploadedFile|null $image
 * @property string|null $notes
 */
class MerchandiseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'image' => 'nullable|image',
            'type' => 'required|exists:merchandise_types,id'
        ];
    }
}
