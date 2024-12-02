<?php

namespace Tests\Actions\Flight;

use App\Actions\Flight\DeleteFlight;
use App\Exceptions\CannotDeleteException;
use App\Models\Flight\Flight;
use App\Models\Order\Component\OrderFlight;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\Prefab\BuildsQuote;
use Tests\Traits\Model\TestsFlight;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\TestsAuthentication;

class DeleteFlightTest extends AuthenticationTestCase
{
    use TestsFlight, TestsOrder, TestsQuote, BuildsQuote, TestsAuthentication;

    public function testHandle(): void
    {
        // Test deleting succeeds with no dependants
        $component = $this->generateFlight();
        $id = $component->id;
        DeleteFlight::run($component);
        $this->assertNull(Flight::find($id));

        // Test deleting fails with a tour dependant
        $tour = $this->generateTour();
        $tourComponent = $tour->flightInventoryTours()->first();
        $this->assertNotNull($tourComponent);
        $component = $tourComponent->inventory->flight;
        $id = $component->id;
        $this->assertNotNull(Flight::find($id));
        try {
            DeleteFlight::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Flight::find($id));
        }

        // Test deleting fails with an order dependant
        $order = $this->generateOrder();
        /** @var OrderFlight|null $orderComponent */
        $orderComponent = $order->orderCustomers()->first()->orderFlights()->first();
        $this->assertNotNull($orderComponent);
        $component = $orderComponent->tourComponent->inventory->flight;
        $id = $component->id;
        $this->assertNotNull(Flight::find($id));
        try {
            DeleteFlight::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Flight::find($id));
        }

        // Test deleting fails with a quote dependant
        $quote = $this->buildBespokeQuote();
        $quoteComponent = $quote->flights()->first();
        $this->assertNotNull($quoteComponent);
        $component = $quoteComponent->inventory->flight;
        $id = $component->id;
        $this->assertNotNull(Flight::find($id));
        try {
            DeleteFlight::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Flight::find($id));
        }
    }

    /**
     * @return void
     * @covers \App\Actions\Flight\DeleteFlight::asController
     */
    public function testJsonCall(): void
    {
        // Verify validation error if id not provided
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.flight.delete'), ['__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(422);

        // Verify authenticated users can call api
        $id = $this->generateFlight()->id;
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.flight.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(204);
        $this->assertNull(Flight::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateFlight()->id;
        $user = $this->guest();
        $response = $this->deleteJson(route('api.admin.flight.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(403);
        $this->assertNotNull(Flight::find($id));

    }

    public function testHtmlCall(): void
    {
        // Verify authenticated users can call api
        $id = $this->generateFlight()->id;
        $user = $this->user();
        $response = $this->actingAs($user)->post(route('flights.delete'), ['id' => $id,]);
        $response->assertRedirect(route('flights.all'));
        $this->assertNull(Flight::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateFlight()->id;
        $user = $this->guest();
        $response = $this->actingAs($user)->post(route('flights.delete'), ['id' => $id,]);
        $response->assertStatus(403);
        $this->assertNotNull(Flight::find($id));
    }
}
