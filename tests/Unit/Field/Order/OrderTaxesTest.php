<?php

namespace Tests\Unit\Field\Order;

use App\Models\Order\Order;
use App\Models\System\TaxBracket;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

class OrderTaxesTest extends DatabaseTestCase
{
    use TestsOrder;

    private function getOrder(float|null $taxes, float $total = 1000, float $surcharge = 0, float $deposit = 0, float|null $commission = null): Order
    {
        if ($taxes !== null) {
            $bracket = TaxBracket::factory()->create(['rate' => $taxes,]);
        } else {
            $bracket = null;
        }
        $order = $this->generateOrder(true, false, $total, $surcharge, $deposit);
        $order->tax_bracket_id = $bracket?->id;
        $order->commission = $commission;
        $order->save();
        return $order;
    }

    public function testNullTaxes(): void
    {
        $order = $this->getOrder(null, 1000, 0, 0);
        $this->assertNull($order->getTaxes());
    }

    public function testTenPercentTaxes(): void
    {
        $order = $this->getOrder(10, 1000, 0, 0);
        $this->assertEquals(100, $order->getTaxes());
    }

    public function testTwentyPercentTaxes(): void
    {
        $order = $this->getOrder(20, 1000, 0, 0);
        $this->assertEquals(200, $order->getTaxes());
    }

    public function testTenPercentTaxesWithTenPercentCommission(): void
    {
        $order = $this->getOrder(10, 1000, 0, 0, 10);
        // Commission should not affect taxes
        $this->assertEquals(100, $order->getTaxes());
    }
}
