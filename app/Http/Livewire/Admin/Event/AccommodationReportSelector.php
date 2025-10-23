<?php

namespace App\Http\Livewire\Admin\Event;

use App\Http\Livewire\Abstract\AccommodationByDateComponent;
use App\Models\Accommodation\Accommodation;
use App\Models\Tour\Event;
use App\Repository\Storage\Report\AccommodationByNightReportStorage;
use Carbon\Carbon;

class AccommodationReportSelector extends AccommodationByDateComponent
{
    public int|null $eId = null;
    public string $type = 'report';

    public function mount(Accommodation|int|null $accommodation = null, Carbon|string|null $start = null, Carbon|string|null $end = null, Event|int|null $event = null)
    {
        $event = Event::getForMount($event);
        parent::mount($accommodation, $start ?? $event->starts_at, $end ?? $event->ends_at);
        $this->eId = $event?->id;
    }

    public function save()
    {
        $storage = new AccommodationByNightReportStorage();
        foreach ($this->fetchData() as $data) {
            if ($this->selected($data)) {
                $storage->addAll($data->inventory);
            }
        }
        return \Excel::download($storage, 'accommodation-by-night.xlsx');
    }

    public function getPackageType(): string
    {
        return 'Event';
    }
    
    public function getReturnUrl(): string
    {
        return route('events.view', ['event' => $this->eId]);
    }
}
