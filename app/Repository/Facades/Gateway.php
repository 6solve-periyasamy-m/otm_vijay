<?php

namespace App\Repository\Facades;

use App\Http\Gateways\AirwallexGateway;
use App\Http\Gateways\DemoGateway;
use App\Http\Gateways\FellohGateway;
use App\Http\Gateways\OpayoGateway;
use App\Http\Gateways\StripeGateway;
use Exception;

class Gateway
{
    private array $gateways = [];

    public function __construct()
    {
        if (config('app.gateways.opayo.vendor') != null) {
            $this->gateways['opayo'] = new OpayoGateway();
        }
        if (config('app.gateways.stripe.secret') != null
            && config('app.gateways.stripe.publishable') != null) {
            $this->gateways['stripe'] = new StripeGateway();
        }
        if (config("app.gateways.felloh.public") != null
            && config("app.gateways.felloh.private") != null
            && config("app.gateways.felloh.organisation") != null) {
            $this->gateways['felloh'] = new FellohGateway();
        }
        if (config('app.gateways.airwallex.client') != null
            && config('app.gateways.airwallex.secret') != null
            && config('app.gateways.airwallex.webhook') != null) {
            try {
                $gateway = new AirwallexGateway();
                $this->gateways['airwallex'] = $gateway;
            } catch (Exception $ignored) {}
        }
        if (config('app.gateways.demo', false) === true
            && config('app.debug', false) === true
            && sizeof($this->gateways) === 0) {
            $this->gateways['demo'] = new DemoGateway();
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
