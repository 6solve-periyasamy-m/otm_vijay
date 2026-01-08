<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Gateways\StripeGateway;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeController extends ApiController
{
    public function webhook(Request $request): JsonResponse
    {
        if (count($this->getWebhookSecrets()) > 0) {
            foreach ($this->getWebhookSecrets() as $secret) {
                $sig = $request->header('Stripe-Signature');
                try {
                    $event = Webhook::constructEvent($request->getContent(), $sig, $secret);
                    $this->processWebhook($event);
                    return response()->json(['success' => true], 200);
                } catch (SignatureVerificationException $e) {
                    continue;
                }
            }
        }
        return response()->json(['success' => false, 'message' => 'Stripe incorrectly configured for webhooks.'], 200);
    }

    private function processWebhook(Event $event): void
    {
        if ($event->type === 'charge.succeeded') {
            $data = $event->data->toArray()['object'];
            $metadata = $data['metadata'];
            $intent = StripeGateway::getPaymentIntent($data['payment_intent']);
            $surcharge = 0;
            if ($intent !== null) {
                $surcharge = $intent->amount_details->toArray()['surcharge']['amount'];
            }
            if (array_key_exists('intention_id', $metadata)) {
                (new StripeGateway())->process($metadata['intention_id'], $data['amount'], Carbon::createFromTimestamp($data['created']), $data['currency'], $surcharge);
            }
        }
    }

    private function getWebhookSecrets(): array
    {
        $secrets = [config('app.gateways.stripe.webhook'),];
        foreach (config('app.gateways.stripe.currencies') as $key => $currency) {
            if (($currency['webhook'] ?? null) !== null) {
                $secrets[] = $currency['webhook'];
            }
        }
        foreach ($secrets as $key => $secret) {
            if (empty($secret)) {
                unset($secrets[$key]);
            }
        }
        return $secrets;
    }
}