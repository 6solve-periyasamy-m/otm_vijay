<?php

namespace App\Listeners;

use App\Http\Gateways\StripeGateway;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class CheckoutSuccessfulListener implements ShouldQueue
{
    public function handle(WebhookCall $call)
    {
        $payload = $call->payload;
        $data = $payload['data']['object'];
        $metadata = $data['metadata'];
        if (key_exists('intention_id', $metadata)) {
            \Log::info($payload['created']);
            (new StripeGateway())->process($metadata['intention_id'], $data['amount'], Carbon::createFromTimestamp($payload['created']));
        }
    }
}
