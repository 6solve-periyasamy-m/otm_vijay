<?php

namespace Field\Order;

use Carbon\Carbon;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * Tests related to getting the number of days until the next payment is due
 * @covers Order::getNextInstallmentAttribute
 * @covers \App\Repository\StaticOrderRepository::getNextPaymentDetails Parent calculation method of Order::getNextInstallmentAttribute
 */
class OrderNextInstallmentTest extends DatabaseTestCase
{
    use TestsOrder;

    /*
     * NB: addDays() leads to the days being X-1, whilst subDays leads to days being X
     */

    /**
     * Test to see if the next installment details returns correctly if there is a single, unpaid installment, in the future
     * @return void
     */
    public function testSingleInstallmentNotPaidInFuture()
    {
        $dueDate = Carbon::now()->addDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly if there is a single, unpaid installment, in the past
     * @return void
     */
    public function testSingleInstallmentNotPaidInPast()
    {
        $dueDate = Carbon::now()->subDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly if there is a single, unpaid installment, due today
     * @return void
     */
    public function testSingleInstallmentNotPaidToday()
    {
        $dueDate = Carbon::now()->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns null, if there is a single, paid, installment
     * @return void
     */
    public function testSingleInstallmentPaid()
    {
        $dueDate = Carbon::now()->subDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $this->generatePayment($installment->order, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertNull($nextInstallment);
    }

    /**
     * Test to see if the function returns properly if there are no installments on the order
     * @return void
     */
    public function testNoInstallmentAvailable()
    {
        $order = $this->generateOrder();
        $this->assertNull($order->next_installment);
    }

    /**
     * Test to see if the next installment details returns correctly if there are multiple, unpaid installment, in the future
     * @return void
     */
    public function testMultiInstallmentFirstNotPaidInFuture()
    {
        $dueDate = Carbon::now()->addDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(20), 100, $installment->order);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly if there are multiple, unpaid installment, one in the past and one in the future
     * @return void
     */
    public function testMultiInstallmentFirstNotPaidInPastAndSecondInFuture()
    {
        $dueDate = Carbon::now()->subDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $this->generateOrderInstallment(Carbon::now()->addDays(20), 100, $installment->order);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly if there are multiple, unpaid installment, in the past
     * This test also serves to check that order of installment creation does not matter for checking
     * @return void
     */
    public function testMultiInstallmentFirstNotPaidInPastAndSecondInLessPast()
    {
        $dueDate = Carbon::now()->subDays(20)->setTime(0,0);
        $installment = $this->generateOrderInstallment($dueDate, 100);
        $this->generateOrderInstallment(Carbon::now()->subDays(10), 100, $installment->order);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly is a single, unpaid installment after a paid one that is due in the future
     * @return void
     */
    public function testMultiInstallmentFirstPaidAndSecondNotPaidInFuture()
    {
        $dueDate = Carbon::now()->addDays(20)->setTime(0,0);
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->generateOrderInstallment($dueDate, 100, $installment->order);
        $this->generatePayment($installment->order, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly if there is a paid installment in the past, and a due one in the future
     * @return void
     */
    public function testMultiInstallmentFirstPaidInPastAndSecondNotPaidInFuture()
    {
        $dueDate = Carbon::now()->addDays(20)->setTime(0,0);
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(10), 100);
        $this->generateOrderInstallment($dueDate, 100, $installment->order);
        $this->generatePayment($installment->order, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly if there is a single, unpaid installment in the past, after a paid one
     * @return void
     */
    public function testMultiInstallmentFirstPaidInPastAndSecondNotPaidInLessPast()
    {
        $dueDate = Carbon::now()->subDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(20), 100);
        $this->generateOrderInstallment($dueDate, 100, $installment->order);
        $this->generatePayment($installment->order, 100);
        $nextInstallment = $installment->order->next_installment;
        $this->assertTrue($dueDate->eq($nextInstallment->due_on));
        $this->assertEquals(100, $nextInstallment->amount);
    }

    /**
     * Test to see if the next installment details returns correctly is a single, unpaid installment after a paid one that is due in the future
     * @return void
     */
    public function testMultiInstallmentAllPaidAndInFuture()
    {
        $dueDate = Carbon::now()->addDays(20)->setTime(0,0);
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->generateOrderInstallment($dueDate, 100, $installment->order);
        $this->generatePayment($installment->order, 200);
        $nextInstallment = $installment->order->next_installment;
        $this->assertNull($nextInstallment);
    }

    /**
     * Test to see if the next installment details returns correctly if all are paid, with one in the past, and one in the future
     * @return void
     */
    public function testMultiInstallmentAllPaidOneInPastOneInFuture()
    {
        $dueDate = Carbon::now()->subDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment(Carbon::now()->addDays(10), 100);
        $this->generateOrderInstallment($dueDate, 100, $installment->order);
        $this->generatePayment($installment->order, 200);
        $nextInstallment = $installment->order->next_installment;
        $this->assertNull($nextInstallment);
    }

    /**
     * Test to see if the next installment details returns correctly if all installments are paid and in the past
     * @return void
     */
    public function testMultiInstallmentAllPaidAndInPast()
    {
        $dueDate = Carbon::now()->subDays(10)->setTime(0,0);
        $installment = $this->generateOrderInstallment(Carbon::now()->subDays(20), 100);
        $this->generateOrderInstallment($dueDate, 100, $installment->order);
        $this->generatePayment($installment->order, 200);
        $nextInstallment = $installment->order->next_installment;
        $this->assertNull($nextInstallment);
    }

}
