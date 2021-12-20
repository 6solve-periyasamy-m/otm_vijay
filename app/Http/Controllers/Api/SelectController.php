<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\OrderCustomer;
use App\Transforms\ActivityTransforms;
use App\Transforms\CustomerTransforms;
use App\Transforms\OrderTransforms;
use App\Transforms\TourTransforms;
use App\Transforms\TransportTransforms;
use App\Transforms\FlightTransforms;
use Illuminate\Http\Request;
use App\Transforms\AccommodationTransforms;
use App\Transforms\LocationsTransforms;

class SelectController extends ApiController
{
    public function getLocations(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectLocations($filter);
    }

    public function getRegions(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectRegions($filter);
    }

    public function getCountries(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectCountries($filter);
    }

    public function getLocationTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectLocationTypes($filter);
    }

    public function getSelectedLocation($id) {
        return LocationsTransforms::getSelectedLocation($id);
    }

    public function getSelectedRegion($id) {
        return LocationsTransforms::getSelectedRegion($id);
    }

    public function getSelectedCountry($id) {
        return LocationsTransforms::getSelectedCountry($id);
    }

    public function getSelectedLocationType($id) {
        return LocationsTransforms::getSelectedLocationType($id);
    }

    public function getRoomTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getSelectRoomTypes($filter);
    }

    public function getSelectedRoomType($id) {
        return AccommodationTransforms::getSelectedRoomType($id);
    }

    public function getBoardTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getSelectBoardTypes($filter);
    }

    public function getSelectedBoardType($id) {
        return AccommodationTransforms::getSelectedBoardType($id);
    }

    public function getTransportTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectTransportTypes($filter);
    }

    public function getOperators(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectOperators($filter);
    }

    public function getTravelClasses(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectTravelClasses($filter);
    }

    public function getSelectedTransportType($id) {
        return TransportTransforms::getSelectedTransportType($id);
    }

    public function getSelectedOperator($id) {
        return TransportTransforms::getSelectedOperator($id);
    }

    public function getSelectedTravelClass($id) {
        return TransportTransforms::getSelectedTravelClass($id);
    }

    public function getActivityTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getSelectActivityTypes($filter);
    }

    public function getTicketTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getSelectTicketTypes($filter);
    }

    public function getSelectedActivityType($id) {
        return ActivityTransforms::getSelectedActivityType($id);
    }

    public function getSelectedTicketTypes($id) {
        return ActivityTransforms::getSelectedTicketType($id);
    }

    public function getEvents(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TourTransforms::getSelectEvents($filter);
    }

    public function getTours(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TourTransforms::getSelectTours($filter);
    }

    public function getSelectedEvent($id) {
        return TourTransforms::getSelectedEvent($id);
    }

    public function getSelectedTour($id) {
        return TourTransforms::getSelectedTour($id);
    }

    public function getAirports(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getSelectAirports($filter);
    }

    public function getAirlines(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getSelectAirlines($filter);
    }

    public function getSelectedAirport($id) {
        return FlightTransforms::getSelectedAirport($id);
    }

    public function getSelectedAirline($id) {
        return FlightTransforms::getSelectedAirline($id);
    }

    public function getAccommodationInventory(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getSelectInventory($filter);
    }

    public function getSelectedAccommodationInventory($id) {
        return AccommodationTransforms::getSelectedInventory($id);
    }

    public function getActivityInventory(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getSelectInventory($filter);
    }

    public function getSelectedActivityInventory($id) {
        return ActivityTransforms::getSelectedInventory($id);
    }

    public function getFlightInventory(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getSelectInventory($filter);
    }

    public function getSelectedFlightInventory($id) {
        return FlightTransforms::getSelectedInventory($id);
    }

    public function getTransportInventory(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectInventory($filter);
    }

    public function getSelectedTransportInventory($id) {
        return TransportTransforms::getSelectedInventory($id);
    }

    public function getQuotes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return OrderTransforms::getSelectQuotes($filter);
    }

    public function getSelectedQuote($id) {
        return OrderTransforms::getSelectedQuote($id);
    }

    public function getCustomers(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return OrderTransforms::getSelectCustomers($filter);
    }

    public function getSelectedCustomer($id) {
        return OrderTransforms::getSelectedCustomer($id);
    }

    public function getHatSizes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return CustomerTransforms::getSelectHatSizes($filter);
    }

    public function getSelectedHatSize($id) {
        return CustomerTransforms::getSelectedHatSize($id);
    }

    public function getTShirtSizes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return CustomerTransforms::getSelectTShirtSizes($filter);
    }

    public function getSelectedTShirtSize($id) {
        return CustomerTransforms::getSelectedTShirtSize($id);
    }

    public function getPaymentMethods(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return OrderTransforms::getSelectPaymentMethods($filter);
    }

    public function getSelectedPaymentMethod($id) {
        return OrderTransforms::getSelectedPaymentMethod($id);
    }

    public function getAddresses(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAddresses($filter);
    }

    public function getSelectedAddress($id) {
        return LocationsTransforms::getSelectedAddress($id);
    }

    public function getCurrencies(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getCurrencies($filter);
    }

    public function getSelectedCurrency($id) {
        return LocationsTransforms::getSelectedCurrency($id);
    }

    public function getAvailableMerchandise(Request $request, OrderCustomer $orderCustomer) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return OrderTransforms::getAvailableMerchandise($orderCustomer, $filter);
    }

    public function getTourCategories(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TourTransforms::getSelectTourCategories($filter);
    }

    public function getSelectedTourCategory($id) {
        return TourTransforms::getSelectedTourCategory($id);
    }
}
