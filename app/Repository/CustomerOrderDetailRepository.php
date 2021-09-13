<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\CustomerOrderDetail;
use Exception;

interface CustomerOrderDetailRepositoryInterface {
    public function getCOD($customerOrderId, $type);
    public function saveCOD($cod, $customerOrderId, $type, $inventory_tour_id, $traveller, $reference);
    public function purge($customerOrderId, $type);
}

class CustomerOrderDetailRepository implements CustomerOrderDetailRepositoryInterface
{
    public function getCOD($customerOrderId, $type)
    {
        $cod = new CustomerOrderDetail();
        $existing = null;
        try {
Log::info('getting COD', [$customerOrderId, $type]);
            $existing = $cod->where('orders_customer_id', $customerOrderId)
                ->where('type', $type)
                ->get();
Log::info('COD', $existing->toArray());
        } catch (Exception $e) {
            Log::info('Failed to get COD ' . $e->getMessage());
        }
        if (is_object($existing)) {
            Log::info('get existing COD', $existing->toArray());
        }
        return $existing;
    }

    public function saveCOD($cod, $customerOrderId, $type, $inventory_tour_id, $traveller, $reference)
    {
        $cod->orders_customer_id = $customerOrderId;
        // $cod->order_id = $order_id;
        $cod->type = $type;

        $cod->type = $type;
        // if ($room) {
        //     $cod->type = $room['board_type_name'] . ' ' . $room['room_type_name'];
        // } else {
        //     $cod->type = "shared room";
        //}
        $cod->date_time = date('Y-m-d H:i:s');

        // repurposed fields
        // $cod->inventory_id = 0;
        // $cod->inventory_tour_id = 0;
        // $cod->addon = $sharer_id ? $sharer_id : 0;
        // $cod->reference = '';
        $cod->inventory_tour_id = $inventory_tour_id;
        $cod->addon = false;
        $cod->cost = 0.00;
        // $cod->save();
        // Log::info('cod', $cod->toArray());
        $cod->reference = $reference;
        try {
            $cod->save();
        } catch(\Exception $e) {
            Log::info('exception'.$e->getMessage());
            throw new \Exception($e->getMessage());
        }
        Log::info('cod created' . $cod->id);
    }

    public function storeCustomerOrderDetail(CustomerOrderDetail $customer_order_detail)
    {
        try {
            $customer_order_detail->save();
            return $customer_order_detail->id;
        } catch(\Exception $e) {
            Log::info('ERROR updating customer order detail'.$e->getMessage());
            throw new Exception('ERROR updating CustomerOrderDetail'. $e->getMessage());
        };
    }

    public function purge($customerOrderId, $type)
    {
        $cod = new CustomerOrderDetail();
        Log::info('purge', [$customerOrderId, $type]);
        $result = $cod->where('orders_customer_id', $customerOrderId)
                ->where('type', $type)
                ->forceDelete();

        return $result;
    }
}
