<?php

namespace App\Http\Gateways;

use App\Http\Gateways\Storage\LineItem;
use App\Models\Order\Payment\PaymentIntention;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

abstract class Gateway
{
    /**
     * @param LineItem[] $items
     * @param PaymentIntention $intention
     * @param string|null $success The redirect URL for
     * @return string The URL for the checkout gateway
     */
    public abstract function checkout(array $items, PaymentIntention $intention, string $success = null): string;

    public abstract function process(string $reference, float $amount, string $created = null): void;

    public function success(Request $request): Factory|View|Application
    {
        return view('pages.payments.success');
    }

    public function cancelled(Request $request): Factory|View|Application
    {
        return view('pages.payments.cancelled');
    }
}
