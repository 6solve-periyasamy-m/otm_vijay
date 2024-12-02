<?php

namespace Tests\Actions\Merchandise;

use App\Actions\Merchandise\DeleteMerchandise;
use App\Exceptions\CannotDeleteException;
use App\Models\Merchandise\Merchandise;
use App\Models\Order\Component\OrderMerchandise;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\Prefab\BuildsQuote;
use Tests\Traits\Model\TestsMerchandise;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\TestsAuthentication;

class DeleteMerchandiseTest extends AuthenticationTestCase
{
    use TestsMerchandise, TestsOrder, TestsQuote, BuildsQuote, TestsAuthentication;

    public function testHandle(): void
    {
        // Test deleting succeeds with no dependants
        $component = $this->generateMerchandise();
        $id = $component->id;
        DeleteMerchandise::run($component);
        $this->assertNull(Merchandise::find($id));

        // Test deleting fails with a tour dependant
        $tour = $this->generateTour();
        $tourComponent = $tour->merchandise()->first();
        $this->assertNotNull($tourComponent);
        $component = $tourComponent->inventory->component;
        $id = $component->id;
        $this->assertNotNull(Merchandise::find($id));
        try {
            DeleteMerchandise::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Merchandise::find($id));
        }

        // Test deleting fails with an order dependant
        $order = $this->generateOrder();
        /** @var OrderMerchandise|null $orderComponent */
        $orderComponent = $order->orderCustomers()->first()->orderMerchandise()->first();
        $this->assertNotNull($orderComponent);
        $component = $orderComponent->tourComponent->inventory->component;
        $id = $component->id;
        $this->assertNotNull(Merchandise::find($id));
        try {
            DeleteMerchandise::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Merchandise::find($id));
        }

        // Test deleting fails with a quote dependant
        $quote = $this->buildBespokeQuote();
        $quoteComponent = $quote->merchandise()->first();
        $this->assertNotNull($quoteComponent);
        $component = $quoteComponent->inventory->component;
        $id = $component->id;
        $this->assertNotNull(Merchandise::find($id));
        try {
            DeleteMerchandise::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Merchandise::find($id));
        }
    }

    /**
     * @return void
     * @covers \App\Actions\Merchandise\DeleteMerchandise::asController
     */
    public function testJsonCall(): void
    {
        // Verify validation error if id not provided
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.merchandise.delete'), ['__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(422);

        // Verify authenticated users can call api
        $id = $this->generateMerchandise()->id;
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.merchandise.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(204);
        $this->assertNull(Merchandise::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateMerchandise()->id;
        $user = $this->guest();
        $response = $this->deleteJson(route('api.admin.merchandise.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(403);
        $this->assertNotNull(Merchandise::find($id));

    }

    public function testHtmlCall(): void
    {
        // Verify authenticated users can call api
        $id = $this->generateMerchandise()->id;
        $user = $this->user();
        $response = $this->actingAs($user)->post(route('merchandise.delete'), ['id' => $id,]);
        $response->assertRedirect(route('merchandise.all'));
        $this->assertNull(Merchandise::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateMerchandise()->id;
        $user = $this->guest();
        $response = $this->actingAs($user)->post(route('merchandise.delete'), ['id' => $id,]);
        $response->assertStatus(403);
        $this->assertNotNull(Merchandise::find($id));
    }
}
