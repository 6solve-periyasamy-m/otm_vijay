<?php

namespace App\Http\Controllers\Api\Customer;

use App\Exceptions\BookingApiException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Customer\BookingV3Controller;
use App\Http\Gateways\StripeGateway;
use App\Http\Requests\Booking\ApiComponentRequest;
use App\Http\Requests\Booking\ApiRoomingRequest;
use App\Http\Requests\Booking\BookingOverviewRequest;
use App\Http\Requests\Booking\SetTravellersRequest;
use App\Http\Requests\Booking\Simple\SetupBookingRequest;
use App\Repository\Model\Booking\BookingTravellerRepository;
use App\Http\Requests\Booking\TourOverviewRequest;
use App\Http\Requests\Booking\ApiDetailsRequest;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Location\Currency;
use App\Repository\Model\Booking\BookingRepository;
use App\Models\Order\Order;
use App\Models\Order\Payment\Payment;
use App\Models\Order\Payment\PaymentIntention;
use App\Models\Order\Payment\PaymentMethod;
use App\Models\Customer\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Gateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Settings;
use DB;

class BookingController extends ApiController
{
    public function overview(TourOverviewRequest $request): JsonResponse
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'A tour with that URL does not exist.',], 422);
        }
        return response()->json([
            'success' => true,
            'booking' => [
                'tour' => $tour->repository->getDataForBooking($request->currency),
            ]
        ]);
    }

    public function booking(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        return response()->json([
            'success' => true,
            'booking' => $request->getBooking()->repository->getSimpleData(),
        ]);
    }

    public function setup(SetupBookingRequest $request): JsonResponse
    {
        $tour = $request->getTour();
        if ($tour === null || !$tour->is_active) {
            return response()->json(['success' => false, 'message' => 'Could not find selected tour'], 404);
        }
        $booking = BookingRepository::createForBookingApi($tour, new BookingTraveller([
            'first_name' => $request->name,
            'email_address' => $request->email,
        ]));

        if (in_array(strtoupper($request->currency), BookingV3Controller::ALLOWED_CURRENCIES)) {
            $booking->currency_id = Currency::where('code', '=', $request->currency)->first()?->id;
            $booking->save();
        }

        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function processRooming(ApiRoomingRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        try {
            $booking->repository->processRoomingFromApi($request->rooming);
        } catch (BookingApiException $e) {
            return response()->json(['success' => false, 'error' => $e->getErrorCode(), 'message' => $e->getMessage()], 400);
        }
        $booking = $booking->refresh();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function processComponents(ApiComponentRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        try {
            $components = $request->components();
            if (!$components) {
                return response()->json([
                    'success' => false,
                    'message' => 'Components are missing'
                ], 422);
            }
            $booking->repository->processComponentsFromApi($components);
        } catch (BookingApiException $e) {
            return response()->json(['success' => false, 'error' => $e->getErrorCode(), 'message' => $e->getMessage()], 400);
        }
        $booking = $booking->refresh();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function processDetails(ApiDetailsRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        $lead = $booking->leadTraveller;

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead traveller not found',
            ], 422);
        }
        try {

            if (empty($lead->email_address)) {
                return response()->json(['success' => false, 'message' => 'Lead traveller missing email for booking'], 400);
            }
            $lead->update([
                'last_name'       => $request->lead_last_name,
                'date_of_birth'   => $request->lead_date_of_birth,
                'mobile_number'   => $request->lead_mobile_number,
            ]);
            $repository = new BookingTravellerRepository($lead);
            $customer = $repository->convertToCustomer();
            $lead->update([
                'customer_id'   => $customer->id,
            ]);
        } catch (BookingApiException $e) {
            return response()->json(['success' => false, 'error' => $e->getErrorCode(), 'message' => $e->getMessage()], 400);
        }
        $booking->notes = $this->sanitizeHtml($request->special_notes);
        $booking->save();
        $booking = $booking->refresh();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function addTraveller(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        $booking->repository->addUnknownTraveller();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function setTravellers(SetTravellersRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        $travellers = $booking->travellers()->count();
        $diff = $request->quantity - $travellers;
        if ($diff < 0) {
            for ($i = 0; $i < abs($diff); $i++) {
                $booking->repository->removeUnknownTraveller();
            }
        } else if ($diff > 0) {
            for ($i = 0; $i < $diff; $i++) {
                $booking->repository->addUnknownTraveller();
            }
        }
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function removeTraveller(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        $booking->repository->removeUnknownTraveller();
        return response()->json(['success' => true, 'booking' => $booking->repository->getSimpleData(),]);
    }

    public function getStripePublishableKey(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        return response()->json(['success' => true, 'publishable' => config('app.gateways.stripe.publishable', null)]);
    }
    public function getStripeSecret(Request $request): JsonResponse
    {
        $booking = Booking::where('token', '=', $request->token)->first();
        if ($booking === null) { return response()->json(['success' => false, 'message' => 'Requested booking was not for the selected tour'], 422); }
        $rate = Settings::getConversionRate(Settings::currency(), $booking->currency?->code) ?? 1.0;
        if ($request->full ?? false) {
            $amount = $booking->repository->getTotalCost() * $rate;
        } else {
            $amount = $booking->repository->getDueTodayAmount() * $rate;
        }
        $keys = $booking->repository->getStripeKey($amount);
        return response()->json(['success' => true, 'intent' => $keys['intent'], 'checkoutSessionClientSecret' => $keys['secret'],]);
    }

    public function assignPaymentMethod(Request $request): void
    {
        $gateway = Gateway::getPaymentGateway('stripe');
        if ($gateway instanceof StripeGateway) {
            $gateway->attachPaymentMethodToIntention($request->secret, $request->paymentMethod);
        }
    }

    protected function sanitizeHtml(?string $html): ?string
    {
        if (!$html) {
            return null;
        }
        $html = preg_replace('#<(script|iframe|object|embed|style)[^>]*>.*?</\1>#is', '', $html); // Remove script, iframe, object, embed
        $html = preg_replace('/(<[^>]+)\s+on\w+\s*=\s*(["\']).*?\2/i', '$1',$html);
        $html = preg_replace('/javascript:/i', '', $html);   // Remove javascript: urls
        return trim($html);
    }

    /**
     * Create order from booking (first step - before payment)
     */
    public function createOrder(BookingOverviewRequest $request): JsonResponse
    {
        $valid = $request->validatePackage();
        if ($valid instanceof JsonResponse) {
            return $valid;
        }
        $booking = $request->getBooking();
        // Validate booking has required data
        if (!$booking->leadTraveller || !$booking->leadTraveller->email_address) {
            return response()->json(['success' => false, 'message' => 'Lead traveller email is required to create order'], 422);
        }

        // Check if order already exists
        if ($booking->order_id) {
            $existingOrder = Order::find($booking->order_id);
            if ($existingOrder) {
                return response()->json([
                    'success' => true,
                    'order' => $existingOrder->repository->getApiOrderData(),
                    'booking' => $booking->repository->getSimpleData(),
                    'message' => 'Order already exists for this booking'
                ]);
            }
        }

        try {
            //dd("Start Order Convert");
            // Convert booking to order
            $order = $booking->repository->convertToOrder();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create order from booking'
                ], 500);
            }

            // Get payment details
            $dueToday = $booking->repository->getDueTodayAmount();
            $totalAmount = $booking->repository->getTotalCost();
            $currency = $booking->repository->getCurrency();

            return response()->json([
                'success' => true,
                'order' => $order->repository->getApiOrderData(),
                'payment' => [
                    'due_today' => $dueToday,
                    'total_amount' => $totalAmount,
                    'currency' => $currency->code ?? 'USD',
                    'currency_symbol' => $currency->symbol ?? '$',
                    'order_id' => $order->id,
                    'booking_reference' => $order->booking_reference
                ],
                'message' => 'Order created successfully. Proceed to payment.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create order: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    private function fetchStripePayment(string $paymentIntentId): PaymentIntent
    {
        $stripe = new StripeClient(config('app.gateways.stripe.secret'));
        return $stripe->paymentIntents->retrieve(
            $paymentIntentId,
            ['expand' => [
                'latest_charge',
                'charges.data.balance_transaction'
                ],
            ]
        );
    }


    /**
     * Confirm Stripe payment and update order
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'booking_token' => 'required|string',
            'payment_intent_id' => 'required|string',
            'payment_method_id' => 'nullable|string',
            'payment_method_details' => 'nullable|array',
            'payment_type' => 'nullable|string',
            'billing_details' => 'nullable|array',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'status' => 'required|string|in:succeeded,processing,requires_action,requires_payment_method,canceled',
            'payment_method_type' => 'nullable|string',
            'receipt_url' => 'nullable|url',
            'metadata' => 'nullable|array',
        ]);

        // Get the booking
        $booking = Booking::where('token', $request->booking_token)->first();

        if (!$booking) {
            return response()->json([ 'success' => false, 'message' => 'Booking not found'], 404);
        }

        // Get the associated order
        if (!$booking->order_id) {
            return response()->json(['success' => false, 'message' => 'No order found for this booking'], 422);
        }

        $order = Order::find($booking->order_id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        try {

            DB::beginTransaction();
            $paymentIntent = $this->fetchStripePayment($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                $error = $paymentIntent->last_payment_error;
                $failurePayload = [
                    'status'   => $paymentIntent->status,
                    'amount'   => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'livemode' => $paymentIntent->livemode,
                    'error'    => $error ? [
                        'type'         => $error->type ?? null,
                        'code'         => $error->code ?? null,
                        'decline_code' => $error->decline_code ?? null,
                        'message'      => $error->message ?? null,
                        'doc_url'      => $error->doc_url ?? null,
                    ] : null,
                    'charge_id' => $error->charge ?? null,
                ];
                PaymentIntention::updateOrCreate(
                    ['id' => $paymentIntent->id],
                    [
                        'customer_id' => $booking->leadTraveller?->customer_id,
                        'type'        => $request->payment_type ?? 'Deposit',
                        'reference'   => $order->booking_reference,
                        'amount'      => $paymentIntent->amount_received / 100,
                        'data'        => json_encode($failurePayload),
                        'processed'   => 0,
                    ]
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed',
                    'status'  => $paymentIntent->status
                ], 422);
            }

            $charge = null;
            if (!empty($paymentIntent->latest_charge)) {
                $charge = is_string($paymentIntent->latest_charge)
                    ? $stripe->charges->retrieve($paymentIntent->latest_charge)
                    : $paymentIntent->latest_charge;
            }

            $gatewayType = 'stripe';
            $paymentMethod = PaymentMethod::findOrCreate($gatewayType);

            //Save Payment Intent snapshot
            PaymentIntention::updateOrCreate(
                ['id' => $paymentIntent->id],
                [
                    'customer_id' => $booking->leadTraveller?->customer_id,
                    'type'        => $request->payment_type ?? 'Deposit',
                    'reference'   => $order->booking_reference,
                    'amount'      => $paymentIntent->amount_received / 100,
                    'data'        => json_encode($paymentIntent->toArray()),
                    'processed'   => $paymentIntent->status === 'succeeded',
                ]
            );

            //Create Payment record
            $payment = Payment::create([
                'order_id'         => $order->id,
                'payment_method_id'=> $paymentMethod->id,
                'payer_id'         => $booking->leadTraveller?->customer_id,
                'payer_type'       => Customer::class,
                'amount'           => $paymentIntent->amount_received / 100,
                'paid_on'          => now(),
                'payment_fee'      => 0 / 100,
                'currency_id'      => $order->currency_id,
                'internal_notes'   => null,
            ]);
            DB::commit();


            // Send booking confirmation email to customer with documents attached
            try {
                $mailer = $order->repository->mailer();
                // Attach invoice and itinerary PDFs if available
                if (method_exists($mailer, 'getInvoiceAttachment')) {
                    $invoice = $mailer->getInvoiceAttachment();
                    if ($invoice) {
                        $mailer->attach($invoice['path'], [
                            'as' => $invoice['name'],
                            'mime' => $invoice['mime'] ?? 'application/pdf',
                        ]);
                    }
                }
                if (method_exists($mailer, 'getItineraryAttachment')) {
                    $itinerary = $mailer->getItineraryAttachment();
                    if ($itinerary) {
                        $mailer->attach($itinerary['path'], [
                            'as' => $itinerary['name'],
                            'mime' => $itinerary['mime'] ?? 'application/pdf',
                        ]);
                    }
                }
                $mailer->sendBookingConfirmation();
            } catch (\Throwable $e) {
                \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Payment confirmed successfully']);
        } catch (\Exception $e) {
            \Log::error('Payment confirmation error: ' . $e->getMessage(), [ 'booking_id' => $booking->id, 'order_id' => $order->id ]);
            return response()->json([ 'success' => false, 'message' => 'Payment confirmation failed', ], 500);
        }            
    }
}
