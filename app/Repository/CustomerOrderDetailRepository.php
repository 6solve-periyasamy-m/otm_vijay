<?php

namespace App\Repository;

use Illuminate\Support\Facades\Log;
use App\Models\CustomerOrderDetail;
use Exception;

interface CustomerOrderDetailRepositoryInterface {
    public function getCOD($customerOrderId, $type);
    public function saveCOD($cod, $customerOrderId, $component_type, $inventory_tour_id, $traveller, $reference, $type);
    public function purge($customerOrderId, $type);
}

class CustomerOrderDetailRepository implements CustomerOrderDetailRepositoryInterface
{
    public function getCOD($customerOrderId, $type)
    {
        $cod = new CustomerOrderDetail();
        try {
            $existing = $cod->where('orders_customer_id', $customerOrderId)
                ->where('component_type', $type)
                ->get();
        } catch (Exception $e) {
            Log::info('Failed to get COD ' . $e->getMessage());
        }
        return $existing;
    }

    public function saveCOD($cod, $customerOrderId, $component_type, $inventory_tour_id, $traveller, $reference, $type)
    {
        $cod->orders_customer_id = $customerOrderId;
        // $cod->order_id = $order_id;
        $cod->type = $type;

        $cod->component_type = $component_type;
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

    public function purge($customerOrderId, $type)
    {
        $cod = new CustomerOrderDetail();
        Log::info('purge', [$customerOrderId, $type]);
        $result = $cod->where('orders_customer_id', $customerOrderId)
                ->where('component_type', $type)
                ->forceDelete();

        return $result;
    }
}
