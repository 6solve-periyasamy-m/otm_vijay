<?php

namespace Tests\Actions\Accommodation;

use App\Actions\Accommodation\DeleteAccommodation;
use App\Exceptions\CannotDeleteException;
use App\Models\Accommodation\Accommodation;
use App\Models\Order\Component\OrderAccommodation;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\Prefab\BuildsQuote;
use Tests\Traits\Model\TestsAccommodation;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\TestsAuthentication;

class DeleteAccommodationTest extends AuthenticationTestCase
{
    use TestsAccommodation, TestsOrder, TestsQuote, BuildsQuote, TestsAuthentication;

    public function testHandle(): void
    {
        // Test deleting succeeds with no dependants
        $component = $this->generateAccommodation();
        $id = $component->id;
        DeleteAccommodation::run($component);
        $this->assertNull(Accommodation::find($id));

        // Test deleting fails with a tour dependant
        $tour = $this->generateTour();
        $tourComponent = $tour->accommodationInventoryTours()->first();
        $this->assertNotNull($tourComponent);
        $component = $tourComponent->inventory->accommodation;
        $id = $component->id;
        $this->assertNotNull(Accommodation::find($id));
        try {
            DeleteAccommodation::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Accommodation::find($id));
        }

        // Test deleting fails with an order dependant
        $order = $this->generateOrder();
        /** @var OrderAccommodation|null $orderComponent */
        $orderComponent = $order->orderCustomers()->first()->orderAccommodation()->first();
        $this->assertNotNull($orderComponent);
        $component = $orderComponent->tourComponent->inventory->accommodation;
        $id = $component->id;
        $this->assertNotNull(Accommodation::find($id));
        try {
            DeleteAccommodation::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Accommodation::find($id));
        }

        // Test deleting fails with a quote dependant
        $quote = $this->buildBespokeQuote();
        $quoteComponent = $quote->accommodation()->first();
        $this->assertNotNull($quoteComponent);
        $component = $quoteComponent->inventory->accommodation;
        $id = $component->id;
        $this->assertNotNull(Accommodation::find($id));
        try {
            DeleteAccommodation::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Accommodation::find($id));
        }
    }

    /**
     * @return void
     * @covers \App\Actions\Accommodation\DeleteAccommodation::asController
     */
    public function testJsonCall(): void
    {
        // Verify validation error if id not provided
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.accommodation.delete'), ['__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(422);

        // Verify authenticated users can call api
        $id = $this->generateAccommodation()->id;
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.accommodation.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(204);
        $this->assertNull(Accommodation::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateAccommodation()->id;
        $user = $this->guest();
        $response = $this->deleteJson(route('api.admin.accommodation.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(403);
        $this->assertNotNull(Accommodation::find($id));

    }

    public function testHtmlCall(): void
    {
        // Verify authenticated users can call api
        $id = $this->generateAccommodation()->id;
        $user = $this->user();
        $response = $this->actingAs($user)->post(route('accommodations.delete'), ['id' => $id,]);
        $response->assertRedirect(route('accommodations.all'));
        $this->assertNull(Accommodation::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateAccommodation()->id;
        $user = $this->guest();
        $response = $this->actingAs($user)->post(route('accommodations.delete'), ['id' => $id,]);
        $response->assertStatus(403);
        $this->assertNotNull(Accommodation::find($id));
    }
}
