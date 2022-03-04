<?php

namespace App\Repository;

use App\Models\OrderCustomer;

class CustomerDashboardRepository
{
    public static function generateItinerary(OrderCustomer $orderCustomer): array
    {
        $data = [];
        foreach ($orderCustomer->orderAccommodation() as $orderComponent) {
            $tourComponent = $orderComponent->tourComponent;
            $inventory = $tourComponent->inventory;
            $component = $inventory->component;
            $data[] = ['start' => $inventory->check_in, 'activity' => 'Room Check In',
                'description' => "{$component->name} ({$inventory->roomType->name}) ({$inventory->boardType})"];
            $data[] = ['start' => $inventory->check_out, 'activity' => 'Room Check Out',
                'description' => "{$component->name} ({$inventory->roomType->name}) ({$inventory->boardType})"];
        }
        foreach ($orderCustomer->orderActivities as $orderComponent) {
            $tourComponent = $orderComponent->tourComponent;
            $inventory = $tourComponent->inventory;
            $component = $inventory->component;
            $data[] = ['start' => $inventory->starts_at, 'activity' => $component->activityType,
                'description' => "{$component->name} ({$component->address}) ({$inventory->ticketType})"];
        }
        foreach ($orderCustomer->orderFlights as $orderComponent) {
            $tourComponent = $orderComponent->tourComponent;
            $inventory = $tourComponent->inventory;
            $component = $inventory->component;
            $data[] = ['start' => $inventory->check_in, 'activity' => 'Flight Check In',
                'description' => "{$component->departureAirport->name} to {$component->arrivalAirport->name} (Check In) ({$inventory->flight_number}) ({$inventory->travelClass})"];
            $data[] = ['start' => $inventory->departs_at, 'activity' => 'Flight Departure',
                'description' => "{$component->departureAirport->name} to {$component->arrivalAirport->name} (Departure) ({$inventory->flight_number}) ({$inventory->travelClass})"];
            $data[] = ['start' => $inventory->arrives_at, 'activity' => 'Flight Arrival',
                'description' => "{$component->departureAirport->name} to {$component->arrivalAirport->name} (Arrival) ({$inventory->flight_number}) ({$inventory->travelClass})"];
        }
        foreach ($orderCustomer->orderTransports as $orderComponent) {
            $tourComponent = $orderComponent->tourComponent;
            $inventory = $tourComponent->inventory;
            $component = $inventory->component;
            $data[] = ['start' => $inventory->departs_at, 'activity' => 'Transport Departure',
                'description' => "{$component->name} ({$component->departureAddress->name} to {$component->arrivalAddress->name}) ({$inventory->travelClass})"];
            $data[] = ['start' => $inventory->arrives_at, 'activity' => 'Transport Arrival',
                'description' => "{$component->name} ({$component->departureAddress->name} to {$component->arrivalAddress->name}) ({$inventory->travelClass})"];
        }

        return self::sortItineraryArray($data);
    }

    private static function sortItineraryArray(array $itinerary): array
    {
        usort($itinerary, function ($a, $b) {
            return $a['start']->unix() <=> $b['start']->unix();
        });
        return $itinerary;
    }
}
