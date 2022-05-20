<?php

namespace Field\Order;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * Tests related to getting the number of days until the next payment is due
 * @covers Order::getDaysUntilNextPaymentAttribute
 */
class OrderDaysUntilTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;

    /*
     * NB: addDays() leads to the days being X-1, whilst subDays leads to days being X
     */

    /**
     * Test to see if the days until next payment returns correctly if there is a single, unpaid installment, in the future
     * @return void
     */
    public function testSingleInstallmentNotPaidInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->assertEquals(9, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there is a single, unpaid installment, in the past
     * @return void
     */
    public function testSingleInstallmentNotPaidInPast()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->assertEquals(-10, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there is a single, unpaid installment, due today
     * @return void
     */
    public function testSingleInstallmentNotPaidToday()
    {
        $installment = $this->generateOrderInstallment(Carbon::now(), 100);
        $this->assertEquals(0, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns null, if there is a single, paid, installment
     * @return void
     */
    public function testSingleInstallmentPaid()
    {
        $installment = $this->generateOrderInstallment(Carbon::now(), 100);
        $this->generatePayment($installment->order, 100);
        $this->assertNull($installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the function returns properly if there are no installments on the order
     * @return void
     */
    public function testNoInstallmentAvailable()
    {
        $order = $this->generateOrder();
        $this->assertNull($order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there are multiple, unpaid installment, in the future
     * @return void
     */
    public function testMultiInstallmentFirstNotPaidInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(20), 100, $installment->order);
        $this->assertEquals(9, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there are multiple, unpaid installment, one in the past and one in the future
     * @return void
     */
    public function testMultiInstallmentFirstNotPaidInPastAndSecondInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(10), 100, $installment->order);
        $this->assertEquals(-10, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there are multiple, unpaid installment, in the past
     * This test also serves to check that order of installment creation does not matter for checking
     * @return void
     */
    public function testMultiInstallmentFirstNotPaidInPastAndSecondInLessPast()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(5), 100);
        $this->generateOrderInstallment(Carbon::now()->subDays(10), 100, $installment->order);
        $this->assertEquals(-10, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly is a single, unpaid installment after a paid one that is due in the future
     * @return void
     */
    public function testMultiInstallmentFirstPaidAndSecondNotPaidInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(20), 100, $installment->order);
        $this->generatePayment($installment->order, 100);
        $this->assertEquals(19, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there is a paid installment in the past, and a due one in the future
     * @return void
     */
    public function testMultiInstallmentFirstPaidInPastAndSecondNotPaidInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(10), 100, $installment->order);
        $this->generatePayment($installment->order, 100);
        $this->assertEquals(9, $installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if there is a single, unpaid installment in the past, after a paid one
     * @return void
     */
    public function testMultiInstallmentFirstPaidInPastAndSecondNotPaidInLessPast()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->subDays(5), 100, $installment->order);
        $this->generatePayment($installment->order, 100);
        $this->assertEquals(-5, $installment->order->days_until_next_payment);
    }/**
     * Test to see if the days until next payment returns correctly is a single, unpaid installment after a paid one that is due in the future
     * @return void
     */
    public function testMultiInstallmentAllPaidAndInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(20), 100, $installment->order);
        $this->generatePayment($installment->order, 200);
        $this->assertNull($installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if all are paid, with one in the past, and one in the future
     * @return void
     */
    public function testMultiInstallmentAllPaidOneInPastOneInFuture()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(10), 100, $installment->order);
        $this->generatePayment($installment->order, 200);
        $this->assertNull($installment->order->days_until_next_payment);
    }

    /**
     * Test to see if the days until next payment returns correctly if all installments are paid and in the past
     * @return void
     */
    public function testMultiInstallmentAllPaidAndInPast()
    {
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->generateOrderInstallment(Carbon::now()->subDays(5), 100, $installment->order);
        $this->generatePayment($installment->order, 200);
        $this->assertNull($installment->order->days_until_next_payment);
    }

}
