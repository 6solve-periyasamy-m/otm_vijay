<?php

namespace App\Repository\Model\Activity;

use App\Models\Activity\Activity;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\Manifest\HasActivityManifest;
use App\Repository\Reporting\Manifest\ActivityManifestRepository;
use App\Repository\Storage\Report\EventActivityReportRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ActivityRepository extends ModelRepository implements HasActivityManifest
{
    private Activity $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function get(): Activity
    {
        return $this->activity;
    }

    public function update(array $data): Activity
    {
        $this->activity->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->activity->save();
    }

    public function delete(): bool
    {
        return $this->activity->delete();
    }

    public function isDeleted(): bool
    {
        return $this->activity->trashed();
    }

    public function __toString(): string
    {
        return "{$this->activity->name} ({$this->activity->activityType}) ({$this->activity->address->region}, {$this->activity->address->country})";
    }

    public function getActivityManifest(): Collection|array
    {
        return $this->activity->orders()->with(ActivityManifestRepository::getRelations())->get();
    }

    public static function find($id): Activity|null
    {
        return Activity::find($id);
    }

    public function getEventActivityReportRow(Carbon $starts_at, Carbon $ends_at): EventActivityReportRow
    {
        $total = 0;
        $used = 0;
        foreach ($this->activity->activityInventory()->whereDate('starts_at' , '>=', $starts_at->subDay())
                     ->whereDate('ends_at' , '<=', $ends_at->addDay())->get() as $inventory) {
            $total += $inventory->repository->getTotalStock();
            $used += $inventory->repository->getUsedStock();
        }
        return new EventActivityReportRow(
            $this->activity->name,
            $this->activity->activityType->name,
            $total,
            $used
        );
    }
}
