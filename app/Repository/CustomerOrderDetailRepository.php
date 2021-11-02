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
            $existing = $cod->where('order_customer_id', $customerOrderId)
                ->where('component_type', $type)
                ->get();
        } catch (Exception $e) {
            Log::info('Failed to get COD ' . $e->getMessage());
        }
        return $existing;
    }

    public function saveCOD($cod, $customerOrderId, $component_type, $inventory_tour_id, $traveller, $reference, $type)
    {
        $cod->order_customer_id = $customerOrderId;
        // $cod->order_id = $order_id;
        $cod->type = $type;

        $cod->component_type = $component_type;
        // if ($room) {
        //     $cod->type = $room['name'] . ' ' . $room['name'];
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
        $result = $cod->where('order_customer_id', $customerOrderId)
                ->where('component_type', $type)
                ->forceDelete();

        return $result;
    }
}
