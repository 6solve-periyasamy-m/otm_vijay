<?php

namespace App\Repository\Facades;

use App\Http\Gateways\FellohGateway;
use App\Http\Gateways\NewFellohGateway;
use App\Http\Gateways\StripeGateway;

class Gateway
{
    private array $gateways = [];

    public function __construct()
    {
        if (config("app.gateways.felloh.public") != null
            && config("app.gateways.felloh.private") != null
            && config("app.gateways.felloh.organisation") != null) {
            $this->gateways['new_felloh'] = new NewFellohGateway();
        }
        if (config('app.gateways.stripe.secret') != null
            && config('app.gateways.stripe.publishable') != null) {
            $this->gateways['stripe'] = new StripeGateway();
        }
        if (config('app.gateways.felloh.client') != null
            && config('app.gateways.felloh.secret') != null
            && config('app.gateways.felloh.connected') != null
            && config('app.gateways.felloh.account') != null) {
            $this->gateways['felloh'] = new FellohGateway();
        }
    }

    public function getDefaultGateway(): ?\App\Http\Gateways\Gateway
    {
        return sizeof($this->gateways) > 0 ? $this->gateways[array_key_first($this->gateways)] : null;
    }

    public function getPaymentGateway(string $name): ?\App\Http\Gateways\Gateway
    {
        if (array_key_exists($name, $this->gateways)) {
            return $this->gateways[$name];
        }
        return null;
    }
}
