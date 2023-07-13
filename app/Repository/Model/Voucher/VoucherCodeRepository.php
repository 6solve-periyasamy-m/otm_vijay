<?php

namespace App\Repository\Model\Voucher;

use App\Models\Tour\Tour;
use App\Models\Voucher\VoucherCode;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\HasStockControl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Str;

class VoucherCodeRepository extends ModelRepository implements HasStockControl
{
    public function __construct(private VoucherCode $voucher) { }

    public static function find(string $code): VoucherCode|null
    {
        return VoucherCode::where('code', '=', Str::upper($code))->first();
    }

    public function get(): Model
    {
        return $this->voucher;
    }

    public function update(array $data): Model
    {
        $data['code'] = Str::upper($data['code']);
        $this->voucher->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        $this->voucher->code = Str::upper($this->voucher->code);
        return $this->voucher->save();
    }

    public function delete(): bool
    {
        if ($this->voucher->orderVouchers()->count() > 0) {
            return false;
        }
        $this->voucher->orderVouchers()->delete();
        $this->voucher->results()->delete();
        return $this->voucher->delete();
    }

    public function isDeleted(): bool
    {
        return $this->voucher->id === null;
    }

    public function __toString(): string
    {
        return "{$this->voucher->name} - {$this->voucher->code}";
    }

    /**
     * @return Collection<Tour>
     */
    public function getTours(): Collection
    {
        $id = $this->voucher->id;
        if ($this->voucher->global) {
            return Tour::whereDoesntHave('excludedVouchers', function ($query) use ($id) {
                $query->where('voucher_codes.id', '=', $id);
            })->get();
        } else {
            return $this->voucher->included;
        }
    }

    public function usable(Tour $tour): bool
    {
        if ($this->voucher->excluded()->where('tours.id', '=', $tour->id)->count() > 0) {
            return false;
        }
        return $this->voucher->global || $this->voucher->included()->where('tours.id', '=', $tour->id)->count() > 0;

    }

    public function getUsedStock(): int
    {
        $orders = $this->voucher->orderCustomers()
            ->join('orders', 'orders.id', '=', 'order_customers.order_id')
            ->where('orders.cancelled', '=', 0);
        $bookings = $this->voucher->bookingTravellers()
            ->join('bookings', 'bookings.id', '=', 'booking_travellers.booking_id')
            ->whereNull('bookings.order_id')
            ->wherePivot('updated_at', '>', now()->subHours(6)->format('Y-m-d H:i:s'));
        return $orders->count() + $bookings->count();
    }

    public function getTotalStock(): int
    {
        return $this->voucher->limit ?? 0;
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalStock() - $this->getUsedStock();
    }

    public function isStockControlActive(): bool
    {
        return $this->voucher->limit > 0;
    }

    public function hasEnoughStock(int $amount = 1): bool
    {
        return $this->getAvailableStock() > $amount;
    }
}
