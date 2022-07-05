<?php

namespace Tests\Traits;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\ActivityType;
use App\Models\Activity\TicketType;

trait TestsActivity
{
    public function generateActivity(ActivityType $type = null): Activity
    {
        if (!isset($type)) $type = $this->generateActivityType();
        return Activity::factory()->create(['activity_type_id' => $type->id,]);
    }

    public function generateActivityInventory(Activity $activity = null, TicketType $ticketType = null, array $attributes = []): ActivityInventory
    {
        if (!isset($activity)) $activity = $this->generateActivity();
        if (!isset($type)) $ticketType = $this->generateTicketType();
        $inventory = ActivityInventory::factory()->makeOne($attributes);
        $inventory->ticket_type_id = $ticketType->id;
        $activity->activityInventory()->save($inventory);
        return $inventory;
    }

    public function generateActivityType(): ActivityType
    {
        return ActivityType::factory()->create();
    }

    public function generateTicketType(): TicketType
    {
        return TicketType::factory()->create();
    }
}
