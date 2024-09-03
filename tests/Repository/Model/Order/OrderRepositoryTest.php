<?php

namespace Tests\Repository\Model\Order;

use App\Mail\TemplatedMailable;
use App\Models\Order\Order;
use App\Repository\Model\Order\OrderRepository;
use Mail;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\TestsMailConfig;

class OrderRepositoryTest extends AuthenticationTestCase
{
    use TestsOrder, TestsMailConfig;

    /**
     * @covers \App\Repository\Model\Order\OrderRepository::processInstallmentForReminder
     * @return void
     */
    public function testProcessInstallmentForReminder(): void
    {
        // Setup data
        $order = $this->generateOrder();
        $order->tour->update(['deposit' => 0, 'booking_fee' => 0, 'final_payment' => now()->addDays(20)]);
        $order->leadBooker->customer->update(['email_address' => 'customer@testing.com']);
        $overdue = $this->generateOrderInstallment(now()->subDays(10), 100);
        $order->installments()->save($overdue);
        $due = $this->generateOrderInstallment(now()->addDays(10), 100);
        $order->installments()->save($due);

        // Setup Mailing
        $this->setMailTemplate('payment-due');
        $this->setMailTemplate('payment-overdue');
        $this->setMailTemplate('final-payment-due');
        $this->setMailTemplate('final-payment-overdue');
        $this->adjustMailConfig();

        // First, Test Overdue Payment
        Mail::fake();
        $order->repository->processInstallmentForReminder($overdue, 100); // 100 days covers the installment
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->assertTo('customer@testing.com') &&
                    $mail->subject('payment-overdue');
        });

        // Second, Test Due Payment
        Mail::fake();
        $order->repository->processInstallmentForReminder($due, 100);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->assertTo('customer@testing.com') &&
                    $mail->subject('payment-due');
        });

        // Third, Test Due Final Payment
        Mail::fake();
        $this->generatePayment($order, 200);
        $order->repository->processInstallmentForReminder($order->repository->generateRemainingOrderInstallment(), 100);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->assertTo('customer@testing.com') &&
                    $mail->subject('final-payment-due');
        });

        // Last, Test Overdue Final Payment
        Mail::fake();
        $order->tour->update(['final_payment' => now()->subDays(10)]);
        $order = Order::find($order->id);
        $order->repository->processInstallmentForReminder($order->repository->generateRemainingOrderInstallment(), 101); // Needs updating since already reminded for period
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->assertTo('customer@testing.com') &&
                $mail->subject('final-payment-overdue');
        });
    }
}
