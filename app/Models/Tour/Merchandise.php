<?php

namespace App\Models\Tour;

use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\OrderCustomer;
use App\Repository\StockRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Merchandise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','tour_component_type','stock','purchase_price','tour_sales_price','notes','image_url'];

    public static function getValidationRules(): array
    {
        return [
            'name',
            'tour_component_type' => [
                'required',
                Rule::in([
                    'Included',
                    'Add-on',
                ])
            ],
            'stock' => 'required|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'required|numeric',];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function orderMerchandise(): HasMany
    {
        return $this->hasMany(OrderMerchandise::class, 'merchandise_id');
    }

    public function __toString(): string
    {
        return "{$this->name}";
    }

    public function addToOrder(OrderCustomer $orderCustomer): OrderMerchandise
    {
        return OrderMerchandise::create([
            'order_customer_id' => $orderCustomer->id,
            'merchandise_id' => $this->id,
            'cost' => $this->tour_sales_price,
        ]);
    }

    public function getUsedStockAttribute(): int
    {
        return StockRepository::getExtraStock($this);
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->stock - $this->used_stock;
    }
}
