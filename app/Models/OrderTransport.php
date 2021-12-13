<?php

namespace App\Models;

use App\Repository\TransportComponentRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTransport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['order_customer_id', 'transport_inventory_tour_id'];

    public static function findByOrderCustomer($orderCustomerId)
    {
        $orderTransports = OrderTransport::where('order_customer_id', $orderCustomerId)->get();

        return $orderTransports;
    }

    public function orderCustomers()
    {
        return $this->belongsTo(OrderCustomer::class);
    }

    public function transport()
    {
        return TransportComponentRepository::getComponentFromOrderComponent($this->id);
    }

    public function transportInventory()
    {
        return TransportComponentRepository::getInventoryFromOrderComponent($this->id);
    }

    public function transportInventoryTour()
    {
        return $this->belongsTo(TransportInventoryTour::class, 'transport_inventory_tour_id');
    }
}
