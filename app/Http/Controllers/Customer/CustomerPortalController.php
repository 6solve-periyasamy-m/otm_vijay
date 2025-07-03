<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\CustomerController;
use App\Models\Order\Order;
use App\Models\Customer\Customer;
use App\Models\Order\OrderCustomer;
use App\Repository\Model\Order\OrderRepository;
use App\Models\System\Faq;

class CustomerPortalController extends CustomerController
{
    public function show(?Order $reference = null, ?Customer $customer = null)
    {
        $orderCustomer = $this->getOrderCustomer($reference, $customer);
        return view('pages.customer.portal', [
            'customer' => $this->user(),
            'orders' => $this->getFilteredOrders(),
            'orderCustomer' => $orderCustomer,
        ]);
    }
    private function getFilteredOrders()
    {
        $search = request('search');

        $ordersQuery = $this->user()?->orders()
            ->with(['tour'])
            ->orderBy('cancelled', 'asc')
            ->orderBy('ordered_on', 'desc');

        if (!empty($search)) {
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('booking_reference', 'like', '%' . $search . '%')
                    ->orWhereHas('tour', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        return $ordersQuery->get();
    }
    private function getOrderCustomer(?Order $reference = null, ?Customer $customer = null): OrderCustomer|null
    {
        $customer = $customer ?? $this->user();
        $order = $reference ??  $customer->repository->getDefaultOrder();
        if (!$this->user()->repository->canEditCustomer($customer)) abort(404);
        // if (!isset($order)) abort(404);
        $oCustomer = null;
        if(isset($order->orderCustomers)){
            foreach ($order->orderCustomers as $orderCustomer) {
                if ($orderCustomer->customer_id === $customer?->id) {
                    $oCustomer = $orderCustomer;
                    break;
                }
            }
        }
        return $oCustomer;
    }
    public function showAtol(Order|string $reference)
    {
        $order = $reference instanceof Order ? $reference : OrderRepository::getFromBookingReference($reference);
        if (!isset($order)) abort(404);
        if ($order->repository->getOrderCustomer($this->user()) === null) abort(404);
        return $order->repository->getAtolRepository()->showAtolCertificate();
    }

    public function showFaq()
    {
        $customer = $this->user();
        $brandId = $customer->brand_id ?? null;

        $faqs = Faq::query()
            ->when($brandId, fn($query) => $query->where('brand_id', $brandId))
            ->orWhereNull('brand_id')
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.customer.faq', compact('faqs'));
    }

}
