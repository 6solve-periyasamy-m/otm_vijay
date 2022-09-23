<?php

namespace App\Repository\Facades;

use App\Http\Gateways\DemoGateway;
use App\Http\Gateways\FellohGateway;
use App\Http\Gateways\StripeGateway;

class Gateway
{
    private array $gateways = [];

    public function __construct()
    {
        if (config('app.gateways.stripe.secret') != null && config('app.gateways.stripe.publishable') != null) {
            $this->gateways['stripe'] = new StripeGateway();
        }
        if (config('app.gateways.felloh.public') != null && config('app.gateways.felloh.public') != null) {
            $this->gateways['felloh'] = new FellohGateway();
        }
        $this->gateways['demo'] = new DemoGateway();
    }

    public function getDefaultGateway(): \App\Http\Gateways\Gateway
    {
        return sizeof($this->gateways) > 0 ? $this->gateways[0] : new DemoGateway();
    }
}
