<?php

namespace App\Repository\Model\Tour;

use App\Models\Activity\Activity;
use App\Models\Order\Order;
use App\Models\Tour\Event;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\Manifest\HasOrderManifest;
use App\Repository\Reporting\Manifest\OrderManifestRepository;
use App\Repository\Storage\Report\EventActivityReportRow;
use Illuminate\Support\Collection;

class EventRepository extends ModelRepository implements HasOrderManifest
{
    private Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public static function find($id): Event|null
    {
        return Event::find($id);
    }

    public function get(): Event
    {
        return $this->event;
    }

    public function update(array $data): Event
    {
        $this->event->update($data);
        $this->save();
        return $this->event;
    }

    public function save(): bool
    {
        return $this->event->save();
    }

    public function delete(): bool
    {
        if ($this->event->tours()->count() > 0 || $this->event->children()->count() > 0) {
            return false;
        }
        return $this->event->delete();
    }

    public function isDeleted(): bool
    {
        return $this->event->id === null || $this->event->deleted_at !== null;
    }

    public function __toString(): string
    {
        return $this->event->name;
    }

    /**
     * @return array<EventActivityReportRow>
     */
    public function getActivityReport(): array
    {
        $data = [];
        // If it is a child event, pull from the parent
        $parent = $this->event->parent ?? $this->event;
        // Fetch the inventory specifically between the start and the end of the current day. Add a day to make sure that all are captured
        /** @var Activity $activity */
        foreach ($parent->activities()->get() as $activity) {
            $data[] = $activity->repository->getEventActivityReportRow($this->event->starts_at, $this->event->ends_at);
        }
        return $data;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrderManifest(): Collection|array
    {
        return $this->event->orders()->with(OrderManifestRepository::getEagerLoads())->get();
    }
}
