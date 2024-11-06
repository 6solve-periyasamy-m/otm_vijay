<?php

namespace Tests\Repository\Model\Quote;

use App\Mail\TemplatedMailable;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Quote\Quote;
use App\Repository\Storage\Quote\CustomerForConversion;
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
        $this->setMailTemplate('quote');

        // First, test that the quote actually sends.
        $succeeded = $quote->repository->resend($sent);
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                    $mail->hasSubject('quote') &&
                    !$mail->hasFrom('staff@testing.com');
        });

        Mail::fake(); // Calling fake() again resets the sending for easier testing

        // Second, test that it works when bcc-ing a single address
        $this->adjustMailConfig("bcc1@testing.com");
        $succeeded = $quote->repository->resend($sent);
        $this->assertTrue($succeeded);
        Mail::assertSent(TemplatedMailable::class, static function (TemplatedMailable $mail) {
            return $mail->hasTo('customer@testing.com') &&
                $mail->hasSubject('quote') &&
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
                $mail->hasSubject('quote') &&
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
                $mail->hasSubject('quote') &&
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
                $mail->hasSubject('quote') &&
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
                $mail->hasSubject('quote') &&
                $mail->hasFrom('staff@testing.com') &&
                $mail->hasBcc('bcc1@testing.com') &&
                $mail->hasBcc('bcc2@testing.com') &&
                $mail->hasBcc('consultant@testing.com') &&
                $mail->hasBcc('staff@testing.com');
        });

    }

    public function testConvert(): void
    {
        $travellers = [
            (new CustomerForConversion(true, true))->setCustomer(Customer::factory()->create()->id),
            (new CustomerForConversion(true, true))->setCustomer(Customer::factory()->create()->id)
        ];

        // Bespoke Quote Test
        $bespokeQuote = $this->buildBespokeQuote();
        $lead = (new CustomerForConversion(true, true))->setCustomer($bespokeQuote->leadTraveller->customer_id);
        $order = $bespokeQuote->repository->convert($lead, $travellers);
        $this->compareOrderToQuote($order, $bespokeQuote);
    }

    private function compareOrderToQuote(Order $order, Quote $quote): void
    {
        // Test Traveller Count
        $this->assertEquals(3, $order->orderCustomers()->where('is_charged', '=', true)->where('is_travelling', '=', true)->count());
        // Test the costs
        $this->assertEquals($quote->getDepositAmount(3), $order->calculated_deposit);
        // TODO: Remove +50 when single occupancy calculated on quote totals
        $this->assertEquals($quote->repository->getTotalCost(3) + 50, $order->total);
        // Match the terms and conditions/invoice footer
        $this->assertEquals($quote->terms, $order->tour->terms);
        $this->assertEquals($quote->invoice_footer, $order->invoice_footer);
        // Quote ID will often get inserted into one of the notes. Must match but can have extra
        $this->assertStringContainsString($quote->internal_notes, $order->internal_notes);
        $this->assertStringContainsString($quote->external_notes, $order->external_notes);
        
        // Check component quantities
        
        /** @var array<int, int> $quantities Inventory id to quantity found */
        $quantities = [];
        
        // Accommodation
        foreach ($order->orderCustomers as $traveller) {
            foreach ($traveller->orderAccommodation as $room) {
                $inventory = $room->accommodationInventoryTour->accommodation_inventory_id;
                $quantities[$inventory] = ($quantities[$inventory] ?? 0) + 1;
            }
        }
        foreach ($quote->accommodation as $accommodation) {
            $this->assertEquals(($accommodation->quantity ?? 3), ($quantities[$accommodation->accommodation_inventory_id] ?? 0));
        }
        
        // Activities
        $quantities = [];
        foreach ($order->orderActivities as $component) {
            $inventory = $component->activityInventoryTour->activity_inventory_id;
            $quantities[$inventory] = ($quantities[$inventory] ?? 0) + 1;
        }
        foreach ($quote->activities as $component) {
            $this->assertEquals(($component->quantity ?? 3), ($quantities[$component->activity_inventory_id] ?? 0));
        }
        
        // Flights
        $quantities = [];
        foreach ($order->orderFlights as $component) {
            $inventory = $component->flightInventoryTour->flight_inventory_id;
            $quantities[$inventory] = ($quantities[$inventory] ?? 0) + 1;
        }
        foreach ($quote->flights as $component) {
            $this->assertEquals(($component->quantity ?? 3), ($quantities[$component->flight_inventory_id] ?? 0));
        }
        
        // Transport
        $quantities = [];
        foreach ($order->orderTransport as $component) {
            $inventory = $component->transportInventoryTour->transport_inventory_id;
            $quantities[$inventory] = ($quantities[$inventory] ?? 0) + 1;
        }
        foreach ($quote->transport as $component) {
            $this->assertEquals(($component->quantity ?? 3), ($quantities[$component->transport_inventory_id] ?? 0));
        }
        
        // Merchandise
        $quantities = [];
        foreach ($order->orderMerchandise as $component) {
            $inventory = $component->merchandise->merchandise_inventory_id;
            $quantities[$inventory] = ($quantities[$inventory] ?? 0) + 1;
        }
        foreach ($quote->merchandise as $component) {
            $this->assertEquals(($component->quantity ?? 3), ($quantities[$component->merchandise_inventory_id] ?? 0));
        }
    }
}
