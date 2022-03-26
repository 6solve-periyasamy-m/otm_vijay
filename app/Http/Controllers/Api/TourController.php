<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Log;

use App\Models\Event;
use App\Models\Tour;

/**
 * tour may belong to an event or not.
 */
class TourController extends ApiController
{
    /**
     * getEvents
     * returns events that have not yet started
     *
     * @return JSON events
     */
    public function getEvents() 
    {
        $events = Event::where('starts_at', '>', date('Y-m-d'))->get();

        return response()->json(['success' => true, 'data' => $events]);
    }

    /**
     * getTour 
     *
     * @param [type] $tour_id
     * @return JSON tour
     */
    public function getTour($tour_id)
    {
        $tour = Tour::find($tour_id);
        if ($tour) {
            return response()->json(['success' => true, 'tour' => $tour]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * getTours 
     *
     * @param OPTIONAL $event_id
     * @return JSON tours for a specific event after today, or all tours if no event specified
     */
    public function getTours($event_id = null) 
    {
        $today = date('Y-m-d');
        if ($event_id) {
            $tours = Tour::where('event_id', $event_id)
                    ->join('events', 'tours.event_id', 'events.id')
                    ->where('events.starts_at', '>', $today)
                    ->get();
        } else {
            $tours = Tour::get();
        }

        return response()->json(['success' => true, 'data' => $tours]);
    }

    /**
     * getTourPrice
     *
     * @param [type] $tour_id
     * @return JSON response with tour_price (per person), accommodation single surchage and deposit
     */
    public function getTourPrice($tour_id)
    {
        $tour = Tour::find($tour_id);
        if ($tour) {
            return response()->json(['success' => true, 'tour_price' => $tour->base_price_per_person, 'single_occupancy_surcharge' => $tour->single_occupancy_surcharge, 'deposit' => $tour->deposit]);
        }
    }
}
