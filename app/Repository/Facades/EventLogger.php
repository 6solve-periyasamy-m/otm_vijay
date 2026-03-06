<?php

namespace App\Repository\Facades;

use App\Models\Helper\Enum\ModelEventType;
use App\Models\System\ModelEvent;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Location;
use Log;

class EventLogger
{
    public function simple(Model|null $model, ModelEventType $type): void
    {
        if ($model === null) { return; }
        $event = ModelEvent::make([
            'target_id' => $model->id,
            'target_type' => $model::class,
            'action' => $type,
            ...$this->populate(),
        ]);
        $event->save();
    }

    public function event(Model|null $from, Model|null $to, ModelEventType $type): void
    {
        $event = ModelEvent::make([
            'target_id' => $to?->id,
            'target_type' => $to::class,
            'action' => $type,
            'from' => $from?->toJson(),
            'to' => $to?->toJson(),
            ...$this->populate(),
        ]);
        $event->save();
    }

    private function populate(): array
    {
        $actor = Auth::user();

        return [
            'actor_id' => $actor?->id,
            'actor_type' => $actor === null ? null : $actor::class,
            'occurred' => now(),
            'ip' => request()->ip(),
            'agent' => request()->userAgent(),
            'location' => $this->getLocationString(),
        ];
    }

    private function getLocationString(): string|null
    {
        try { $location = Location::get(); } catch (Exception $exception) { Log::error($exception); return null; }
        if (is_bool($location)) { return null; }
        return "{$location->cityName}, {$location->countryName}, {$location->zipCode}";
    }

      /**
     * Get location info (city, state) for a given IP address.
     * @param string $ip
     * @return array|null [ 'city' => ..., 'state' => ... ]
     */
    public static function getLocationByIp(string $ip): ?array
    {
        try {
            $location = \Location::get($ip);
        } catch (\Exception $exception) {
            \Log::error($exception);
            return null;
        }
        if (!$location || is_bool($location)) {
            return null;
        }
        return [
          //  dd($location),
            'countryCode' => $location->countryCode ?? null,
            'countryName' => $location->countryName ?? null,
            'state' => $location->regionName ?? null,
            'city' => $location->cityName ?? null,
            'timezone' => $location->timezone ?? null,
        ];
    }
}
