<?php

namespace App\View\Components\Admin\Accommodation;

use App\Repository\Storage\Rooming\AccommodationByDateStorage as Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccommodationByDate extends Component
{
    public Storage $storage;
    public CarbonPeriod $period;
    public bool $selected;

    public function __construct(Storage $storage, Carbon $start, Carbon $end, $selected)
    {
        $this->storage = $storage;
        $this->period = CarbonPeriod::create($start, '1 day', $end->subDay());
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.accommodation.accommodation-by-date');
    }
}
