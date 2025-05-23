<?php

namespace Tests\Actions\Activity;

use App\Actions\Activity\DeleteActivity;
use App\Exceptions\CannotDeleteException;
use App\Models\Activity\Activity;
use Tests\Bases\Authentication\AuthenticationTestCase;
use Tests\Traits\Model\Prefab\BuildsQuote;
use Tests\Traits\Model\TestsActivity;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;
use Tests\Traits\TestsAuthentication;

class DeleteActivityTest extends AuthenticationTestCase
{
    use TestsActivity, TestsOrder, TestsQuote, BuildsQuote, TestsAuthentication;

    public function testHandle(): void
    {
        // Test deleting succeeds with no dependants
        $component = $this->generateActivity();
        $id = $component->id;
        DeleteActivity::run($component);
        $this->assertNull(Activity::find($id));

        // Test deleting fails with a tour dependant
        $tour = $this->generateTour();
        $tourComponent = $tour->activityInventoryTours()->first();
        $this->assertNotNull($tourComponent);
        $component = $tourComponent->inventory->activity;
        $id = $component->id;
        $this->assertNotNull(Activity::find($id));
        try {
            DeleteActivity::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Activity::find($id));
        }

        // Test deleting fails with an order dependant
        $order = $this->generateOrder();
        $orderComponent = $order->orderCustomers()->first()->orderActivities()->first();
        $this->assertNotNull($orderComponent);
        $component = $orderComponent->tourComponent->inventory->activity;
        $id = $component->id;
        $this->assertNotNull(Activity::find($id));
        try {
            DeleteActivity::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Activity::find($id));
        }

        // Test deleting fails with a quote dependant
        $quote = $this->buildBespokeQuote();
        $quoteComponent = $quote->activities()->first();
        $this->assertNotNull($quoteComponent);
        $component = $quoteComponent->inventory->activity;
        $id = $component->id;
        $this->assertNotNull(Activity::find($id));
        try {
            DeleteActivity::run($component);
            $this->fail("Expected exception not thrown");
        } catch (CannotDeleteException $e) {
            $this->assertInstanceOf(CannotDeleteException::class, $e);
            $this->assertNotNull(Activity::find($id));
        }
    }

    /**
     * @return void
     * @covers \App\Actions\Activity\DeleteActivity::asController
     */
    public function testJsonCall(): void
    {
        // Verify validation error if id not provided
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.activity.delete'), ['__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(422);

        // Verify authenticated users can call api
        $id = $this->generateActivity()->id;
        $user = $this->user();
        $response = $this->deleteJson(route('api.admin.activity.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(204);
        $this->assertNull(Activity::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateActivity()->id;
        $user = $this->guest();
        $response = $this->deleteJson(route('api.admin.activity.delete'), ['id' => $id, '__api_token' => $user->getCurrentToken()->token,]);
        $response->assertStatus(403);
        $this->assertNotNull(Activity::find($id));

    }

    public function testHtmlCall(): void
    {
        // Verify authenticated users can call api
        $id = $this->generateActivity()->id;
        $user = $this->user();
        $response = $this->actingAs($user)->post(route('activities.delete'), ['id' => $id,]);
        $response->assertRedirect(route('activities.all'));
        $this->assertNull(Activity::find($id));

        // Verify unauthenticated users cannot call api
        $id = $this->generateActivity()->id;
        $user = $this->guest();
        $response = $this->actingAs($user)->post(route('activities.delete'), ['id' => $id,]);
        $response->assertStatus(403);
        $this->assertNotNull(Activity::find($id));
    }
}
