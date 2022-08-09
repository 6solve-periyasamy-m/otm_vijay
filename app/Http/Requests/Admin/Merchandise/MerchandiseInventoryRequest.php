<?php

namespace App\Http\Requests\Admin\Merchandise;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $fit_selectable
 * @property int $stock
 * @property float $purchase_price
 * @property float $sales_price
 * @property int $variant
 * @property int|null $size
 * @property string $notes
 * @property UploadedFile|null $image
 */
class MerchandiseInventoryRequest extends FormRequest
{
    public function getDataset(): array
    {
        return [
            'variant_id' => $this->variant,
            'merchandise_size_id' => $this->size,
            'fit_selectable' => $this->fit_selectable == 'on',
            'stock' => $this->stock,
            'purchase_price' => $this->purchase_price,
            'sales_price' => $this->sales_price,
            'notes' => $this->notes,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'stock' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sales_price' => 'nullable|numeric|min:0',
            'variant' => 'required|exists:variants,id',
            'size' => 'nullable|exists:merchandise_sizes,id',
        ];
    }
}
