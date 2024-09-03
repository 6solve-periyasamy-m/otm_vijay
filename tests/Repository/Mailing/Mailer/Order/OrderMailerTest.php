<?php

namespace Tests\Repository\Mailing\Mailer\Order;

use App\Exceptions\MailFailedException;
use App\Mail\TemplatedMailable;
use Auth;
use Illuminate\Support\Facades\Mail;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\TestsMailConfig;

class OrderMailerTest extends AuthenticationTestCase
{
    use TestsOrder, TestsMailConfig;

    /**
     * @return void
     * @covers \App\Repository\Mailing\Mailer\Order\OrderMailer::sendBookingConfirmation
     * @throws MailFailedException
     */
    public function testSendBookingConfirmation(): void
    {
        // Authentication Setup
        $user = $this->user();
        $user->email = 'staff@testing.com';
        $user->save();
        Auth::login($user);

        // Order Setup
        $order = $this->generateOrder();
        $consultant = $this->generateUser();
        $consultant->update(['email' => 'consultant@testing.com']);
        $order->update(['consultant_id' => $consultant->id]);
        $order->leadBooker->customer->update(['email_address' => 'customer@testing.com',]);

        Mail::fake();
        $this->adjustMailConfig();

        // First, test that the order actually sends.
        $succeeded = $order->repository->mailer()->sendBookingConfirmation();
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                !$mail->hasFrom('staff@testing.com');
        });

        Mail::fake(); // Calling fake() again resets the sending for easier testing

        // Second, test that it works when bcc-ing a single address
        $this->adjustMailConfig("bcc1@testing.com");
        $succeeded = $order->repository->mailer()->sendBookingConfirmation();
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                !$mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com');
        });

        Mail::fake();

        // Third, test that it works with semicolon seperated bccs
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com");
        $succeeded = $order->repository->mailer()->sendBookingConfirmation();
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                !$mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com');
        });

        // Fourth, test that it works with semicolon seperated bccs and sending as logged-in user
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com", true);
        $succeeded = $order->repository->mailer()->sendBookingConfirmation();
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                $mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com');
        });

        // Fifth, test that it works with semicolon seperated bccs and sending as logged-in user, and bccing the sending user
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com", true, true);
        $succeeded = $order->repository->mailer()->sendBookingConfirmation();
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                $mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com') &&
                $mail->hasBcc('staff@testing.com');
        });

        // Finally, test that it works with semicolon seperated bccs and sending as logged-in user, and bccing the sending user + consultant
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com", true, true, true);
        $succeeded = $order->repository->mailer()->sendBookingConfirmation();
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                $mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com') &&
                $mail->hasBcc('consultant@testing.com') &&
                $mail->hasBcc('staff@testing.com');
        });

    }
}
