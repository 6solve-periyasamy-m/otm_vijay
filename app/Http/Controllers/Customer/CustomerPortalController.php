<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\CustomerController;
use App\Models\Order\Order;
use App\Repository\Model\Order\OrderRepository;
use App\Models\System\Faq;

class CustomerPortalController extends CustomerController
{
    public function show()
    {
        return view('pages.customer.portal', ['customer' => $this->user(),]);
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
