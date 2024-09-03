<?php

namespace Tests\Repository\Model\Quote;

use App\Mail\TemplatedMailable;
use Auth;
use Illuminate\Support\Facades\Mail;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\TestsAuthentication;
use Tests\Traits\TestsMailConfig;

class QuoteRepositoryTest extends AuthenticationTestCase
{
    use TestsQuote, TestsMailConfig, TestsAuthentication;

    public function testResend(): void
    {
        // Authentication Setup
        $user = $this->user();
        $user->email = 'staff@testing.com';
        $user->save();
        Auth::login($user);

        // Quote Setup
        $quote = $this->generateQuote();
        $consultant = $this->generateUser();
        $consultant->update(['email' => 'consultant@testing.com']);
        $quote->update(['consultant_id' => $consultant->id]);
        $quote->leadTraveller->customer->update(['email_address' => 'customer@testing.com',]);
        $sent = $quote->repository->generateSent('customer@testing.com', 1,0);


        Mail::fake();
        $this->adjustMailConfig();

        // First, test that the quote actually sends.
        $succeeded = $quote->repository->resend($sent);
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                    !$mail->hasFrom('staff@testing.com');
        });

        Mail::fake(); // Calling fake() again resets the sending for easier testing

        // Second, test that it works when bcc-ing a single address
        $this->adjustMailConfig("bcc1@testing.com");
        $succeeded = $quote->repository->resend($sent);
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                !$mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com');
        });

        Mail::fake();
        
        // Third, test that it works with semicolon seperated bccs
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com");
        $succeeded = $quote->repository->resend($sent);
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                !$mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com');
        });
        
        // Fourth, test that it works with semicolon seperated bccs and sending as logged-in user
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com", true);
        $succeeded = $quote->repository->resend($sent);
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                $mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com');
        });
        
        // Fifth, test that it works with semicolon seperated bccs and sending as logged-in user, and bccing the sending user 
        $this->adjustMailConfig("bcc1@testing.com;bcc2@testing.com", true, true);
        $succeeded = $quote->repository->resend($sent);
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
        $succeeded = $quote->repository->resend($sent);
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
