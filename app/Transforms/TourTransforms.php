<?php

namespace App\Transforms;

use App\Models\System\Brand;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Models\Tour\TourCategory;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use App\Repository\Model\Activity\ActivityInventoryRepository;
use App\Repository\Model\Flight\FlightInventoryRepository;
use App\Repository\Model\Transport\TransportInventoryRepository;
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
    public static function getSelectTourCategories($filter);
    public static function getSelectedTourCategory($id);
}

class TourTransforms implements TourTransformsInterface
{

    public static function getSelectTours($filter)
    {
        $data = [];
        foreach (Tour::all() as $tour) {
            $subData = [];
            $subData['id'] = $tour->id;
            $subData['text'] = isset($tour->event) ? $tour->name . ' - ' . $tour->event->name : $tour->name . ' - No Event';
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectEvents($filter)
    {
        $data = [];
        foreach (Event::all() as $event) {
            // Only include events that match the event_category filter
            if ($event->event_category == 0) {
                $subData = [];
                $subData['id'] = $event->id;
                $subData['text'] = $event->name;
                if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
            }
        }
        return $data;
    }

    public static function getSelectedTour($id)
    {
        if ($id == 0) return null;
        $tour = Tour::findOrFail($id);
        $data = [];
        $data['id'] = $tour->id;
        $data['text'] = isset($tour->event) ? $tour->name . ' - ' . $tour->event->name : $tour->name . ' - No Event';
        return $data;
    }

    public static function getSelectedEvent($id)
    {
        if ($id == 0) return null;
        $event = Event::findOrFail($id);
        $data = [];
        $data['id'] = $event->id;
        $data['text'] = $event->name;
        return $data;
    }

    public static function getAccommodationInventoryDataTable(Tour $tour, $from = "", $to = ""): array
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return ["data" => AccommodationInventoryRepository::getBetweenDates($dateFrom, $dateTo, $tour->repository),];
    }

    public static function getActivityInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return ["data" => ActivityInventoryRepository::getBetweenDates($dateFrom, $dateTo, $tour->repository),];
    }

    public static function getTransportInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return ["data" => TransportInventoryRepository::getBetweenDates($dateFrom, $dateTo, $tour->repository),];
    }

    public static function getFlightInventoryDataTable(Tour $tour, $from = "", $to = "")
    {
        $dateFrom = null;
        $dateTo = null;
        try { if (!empty($from)) $dateFrom = Carbon::parse($from); } catch (InvalidFormatException $ignored) {}
        try { if (!empty($to)) $dateTo = Carbon::parse($to); } catch (InvalidFormatException $ignored) {}
        return ["data" => FlightInventoryRepository::getBetweenDates($dateFrom, $dateTo, $tour->repository),];
    }

    public static function getSelectTourCategories($filter)
    {
        $data = [];
        foreach (TourCategory::all() as $category) {
            $subData = [];
            $subData['id'] = $category->id;
            $subData['text'] = $category->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedTourCategory($id)
    {
        if ($id == 0) return null;
        $category = TourCategory::findOrFail($id);
        $data = [];
        $data['id'] = $category->id;
        $data['text'] = $category->name;
        return $data;
    }

    public static function getSelectBrands($filter): array
    {
        $data = [];
        $subData = ['id' => 0, 'text' => 'Use System Brand'];
        if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        foreach (Brand::all() as $brand) {
            $subData = [];
            $subData['id'] = $brand->id;
            $subData['text'] = $brand->name;
            if (str_contains(strtolower($subData['text']), strtolower($filter))) $data['results'][] = $subData;
        }
        return $data;
    }

    public static function getSelectedBrand($id): array
    {
        $brand = Brand::find($id);
        if ($id == 0 || $id == null || $brand == null) {
            return ['id' => 0, 'text' => 'Use System Brand'];
        }
        return ['id' => $brand->id, 'text' => $brand->name,];
    }
}
