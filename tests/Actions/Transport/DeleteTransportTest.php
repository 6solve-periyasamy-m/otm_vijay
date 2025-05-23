<?php

namespace Tests\Actions\Transport;

use App\Actions\Transport\DeleteTransport;
use App\Exceptions\CannotDeleteException;
use App\Models\Order\Component\OrderTransport;
use App\Models\Transport\Transport;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\Prefab\BuildsQuote;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\Model\TestsTransport;
use Tests\Traits\TestsAuthentication;

class DeleteTransportTest extends AuthenticationTestCase
{
    use TestsTransport, TestsOrder, TestsQuote, BuildsQuote, TestsAuthentication;

    public function testHandle(): void
    {
        // Test deleting succeeds with no dependants
        $component = $this->generateTransport();
        $id = $component->id;
        DeleteTransport::run($component);
        $this->assertNull(Transport::find($id));

        // Test deleting fails with a tour dependant
        $tour = $this->generateTour();
        $tourComponent = $tour->transportInventoryTours()->first();
        $this->assertNotNull($tourComponent);
        $component = $tourComponent->inventory->transport;
        $id = $component->id;
        $this->assertNotNull(Transport::find($id));
        try {
            DeleteTransport::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Transport::find($id));
        }

        // Test deleting fails with an order dependant
        $order = $this->generateOrder();
        /** @var OrderTransport|null $orderComponent */
        $orderComponent = $order->orderCustomers()->first()->orderTransports()->first();
        $this->assertNotNull($orderComponent);
        $component = $orderComponent->tourComponent->inventory->transport;
        $id = $component->id;
        $this->assertNotNull(Transport::find($id));
        try {
            DeleteTransport::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Transport::find($id));
        }

        // Test deleting fails with a quote dependant
        $quote = $this->buildBespokeQuote();
        $quoteComponent = $quote->transport()->first();
        $this->assertNotNull($quoteComponent);
        $component = $quoteComponent->inventory->transport;
        $id = $component->id;
        $this->assertNotNull(Transport::find($id));
        try {
            DeleteTransport::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Transport::find($id));
        }
    }

    /**
     * @return void
     * @covers \App\Actions\Transport\DeleteTransport::asController
     */
    public function testJsonCall(): void
    {
        // Verify validation error if id not provided
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.transport.delete'), ['__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(422);

        // Verify authenticated users can call api
        $id = $this->generateTransport()->id;
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.transport.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(204);
        $this->assertNull(Transport::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateTransport()->id;
        $user = $this->guest();
        $response = $this->deleteJson(route('api.admin.transport.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(403);
        $this->assertNotNull(Transport::find($id));

    }

    public function testHtmlCall(): void
    {
        // Verify authenticated users can call api
        $id = $this->generateTransport()->id;
        $user = $this->user();
        $response = $this->actingAs($user)->post(route('transports.delete'), ['id' => $id,]);
        $response->assertRedirect(route('transports.all'));
        $this->assertNull(Transport::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateTransport()->id;
        $user = $this->guest();
        $response = $this->actingAs($user)->post(route('transports.delete'), ['id' => $id,]);
        $response->assertStatus(403);
        $this->assertNotNull(Transport::find($id));
    }
}
