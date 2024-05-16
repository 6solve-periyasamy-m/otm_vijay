<?php

namespace App\Repository\Facades;

use App\Models\Helper\ModelEventType;
use App\Models\System\ModelEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Location;
use Log;

class EventLogger
{
    public function simple(Model $model, ModelEventType $type): void
    {
        $event = $this->populate(ModelEvent::make([
            'target_id' => $model->id,
            'target_type' => $model::class,
            'action' => $type,
        ]));
        $event->save();
    }

    public function event(Model|null $from, Model|null $to, ModelEventType $type): void
    {
        $event = $this->populate(ModelEvent::make([
            'target_id' => $to->id,
            'target_type' => $to::class,
            'action' => $type,
            'from' => $from->toJson(),
            'to' => $to->toJson(),
        ]));
        $event->save();
    }

    private function populate(ModelEvent $event): ModelEvent
    {
        $actor = Auth::user();
        try { $location = Location::get(); } catch (\Exception $exception) { Log::error($exception); }
        $event->update([
            'actor_id' => $actor->id,
            'actor_type' => $actor::class,
            'occurred' => now(),
            'ip' => request()->ip(),
            'agent' => request()->userAgent(),
            'location' => $location ?? null,
        ]);
        return $event;
    }
}