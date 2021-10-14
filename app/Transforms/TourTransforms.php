<?php

namespace App\Transforms;

use App\Models\Event;
use App\Models\Tour;
use App\Repository\AccommodationComponentRepository;
use App\Repository\ActivityComponentRepository;
use App\Repository\FlightComponentRepository;
use App\Repository\TransportComponentRepository;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

interface TourTransformsInterface {
    public static function getSelectTours($filter);
    public static function getSelectEvents($filter);
    public static function getSelectedTour($id);
    public static function getSelectedEvent($id);
    public static function getAccommodationInventoryDataTable(Tour $tour, $from = "", $to = "");
    public static function getActivityInventoryDataTable(Tour $tour, $from = "", $to = "");
    public static function getTransportInventoryDataTable(Tour $tour, $from = "", $to = "");
    public static function getFlightInventoryDataTable(Tour $tour, $from = "", $to = "");
}

class TourTransforms implements TourTransformsInterface
{

    public static function getSelectTours($filter)
    {
        $data = [];
        foreach (Tour::all() as $tour) {
            $subData = [];
            $subData['id'] = $tour->id;
            $subData['text'] = $tour->title . ' - ' . $tour->event->event_title;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectEvents($filter)
    {
        $data = [];
        foreach (Event::all() as $event) {
            $subData = [];
            $subData['id'] = $event->id;
            $subData['text'] = $event->event_title;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedTour($id)
    {
        if ($id == 0) return null;
        $tour = Tour::findOrFail($id);
        $data = [];
        $data['id'] = $tour->id;
        $data['text'] = $tour->title . ' - ' . $tour->event->event_title;
        return $data;
    }

    public static function getSelectedEvent($id)
    {
        if ($id == 0) return null;
        $event = Event::findOrFail($id);
        $data = [];
        $data['id'] = $event->id;
        $data['text'] = $event->event_title;
        return $data;
    }

    public static function getAccommodationInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return AccommodationComponentRepository::getAvailableBetweenDates($tour, $dateFrom, $dateTo);
    }

    public static function getActivityInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return ActivityComponentRepository::getBetweenDates($tour, $dateFrom, $dateTo);
    }

    public static function getTransportInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return TransportComponentRepository::getBetweenDates($tour, $dateFrom, $dateTo);
    }

    public static function getFlightInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return FlightComponentRepository::getBetweenDates($tour, $dateFrom, $dateTo);
    }
}
