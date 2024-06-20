<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItineraryRepository
{
    public readonly Order $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getResponseStream(OrderCustomer $orderCustomer): StreamedResponse
    {
        return match ((int)setting('itinerary.style', 1)) {
            2 => dompdf(view('pdf.invoices.itinerary', ['order' => $this->order, 'itinerary' => $this->order->repository->getItinerary(),])),
            default => dompdf(view('pdf.itinerary', ['order' => $this->order, 'orderCustomer' => $orderCustomer,])),
        };
    }

}
