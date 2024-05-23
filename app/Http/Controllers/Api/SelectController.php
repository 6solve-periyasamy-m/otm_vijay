<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\System\Bank;
use App\Models\Transport\TransportInventoryTour;
use App\Transforms\AccommodationTransforms;
use App\Transforms\ActivityTransforms;
use App\Transforms\CustomerTransforms;
use App\Transforms\FlightTransforms;
use App\Transforms\LocationsTransforms;
use App\Transforms\MerchandiseTransforms;
use App\Transforms\OrderTransforms;
use App\Transforms\TourTransforms;
use App\Transforms\TransportTransforms;
use Illuminate\Http\Request;

class SelectController extends ApiController
{

    public function getCountries(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectCountries($filter);
    }

    public function getLocationTypes(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return LocationsTransforms::getAvailableSelectLocationTypes($filter);
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

    public function getActivityEvents(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getActivityEvents($filter);
    }

    public function getTours(Request $request) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TourTransforms::getSelectTours($filter);
    }

    public function getSelectedEvent($id) {
        return TourTransforms::getSelectedEvent($id);
    }

    public function getSelectedActivityEvent($id) {
        return ActivityTransforms::getSelectedActivityEvent($id);
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

    public function getAvailableCustomers(Request $request, Order $order) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return OrderTransforms::getSelectCustomers($filter, $order);
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
        $customers = $request->has('customers') && $request->boolean('customers');
        return LocationsTransforms::getAddresses($filter, $customers);
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
    
    public function getAccommodationInventoryForUpgrade(Request $request, AccommodationInventoryTour $inventoryTour) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getSelectInventoryForAccommodation($inventoryTour, $filter);
    }
    
    public function getSelectedAccommodationInventoryForUpgrade($id) {
        return AccommodationTransforms::getSelectedInventoryForAccommodation($id);
    }
    
    public function getActivityInventoryForUpgrade(Request $request, ActivityInventoryTour $inventoryTour) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getSelectInventoryForActivity($inventoryTour, $filter);
    }
    
    public function getSelectedActivityInventoryForUpgrade($id) {
        return ActivityTransforms::getSelectedInventoryForActivity($id);
    }
    
    public function getFlightInventoryForUpgrade(Request $request, FlightInventoryTour $inventoryTour) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getSelectInventoryForFlight($inventoryTour, $filter);
    }
    
    public function getSelectedFlightInventoryForUpgrade($id) {
        return FlightTransforms::getSelectedInventoryForFlight($id);
    }
    
    public function getTransportInventoryForUpgrade(Request $request, TransportInventoryTour $inventoryTour) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getSelectInventoryForTransport($inventoryTour, $filter);
    }
    
    public function getSelectedTransportInventoryForUpgrade($id) {
        return TransportTransforms::getSelectedInventoryForTransport($id);
    }

    public function getAvailableAccommodation(Request $request, OrderCustomer $orderCustomer) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return AccommodationTransforms::getAvailableAddons($orderCustomer, $filter);
    }

    public function getAvailableActivities(Request $request, OrderCustomer $orderCustomer) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return ActivityTransforms::getAvailableAddons($orderCustomer, $filter);
    }

    public function getAvailableFlights(Request $request, OrderCustomer $orderCustomer) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return FlightTransforms::getAvailableAddons($orderCustomer, $filter);
    }

    public function getAvailableTransport(Request $request, OrderCustomer $orderCustomer) {
        $filter = $request->has('filter') ? $request->input('filter') : "";
        return TransportTransforms::getAvailableAddons($orderCustomer, $filter);
    }

    public function getAvailableMerchandiseTypes(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        return MerchandiseTransforms::getMerchandiseTypes($filter);
    }
    
    public function getSelectedMerchandiseType($id)
    {
        return MerchandiseTransforms::getSelectedMerchandiseType($id);
    }

    public function getAvailableVariants(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        return MerchandiseTransforms::getVariants($filter);
    }
    
    public function getSelectedVariant($id)
    {
        return MerchandiseTransforms::getSelectedVariant($id);
    }

    public function getAvailableSizes(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        return MerchandiseTransforms::getSizes($filter);
    }
    
    public function getSelectedSize($id)
    {
        return MerchandiseTransforms::getSelectedSize($id);
    }

    public function getAvailableOrganizations(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        return CustomerTransforms::getSelectOrganizations($filter);
    }
    
    public function getSelectedOrganization($id)
    {
        return CustomerTransforms::getSelectedOrganization($id);
    }

    public function getAvailableBrands(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        return TourTransforms::getSelectBrands($filter);
    }
    
    public function getSelectedBrand($id)
    {
        return TourTransforms::getSelectedBrand($id);
    }

    public function getAvailableFilterCountries(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        return LocationsTransforms::getFilterCountries($filter);
    }

    public function getSelectedFilterCountries($id)
    {
        return LocationsTransforms::getSelectedFilterCountry($id);
    }

    public function getAvailableBanks(Request $request)
    {
        $filter = $request->has('filter') ? $request->filter : "";
        $data = [];
        /** @var Bank $bank */
        foreach (Bank::where('name', 'like', "%$filter%")->get() as $bank) {
            $name = "{$bank->name} - {$bank->address_line_1}, {$bank->country}";
            $data['results'][] = ['id' => $bank->id, 'text' => "$name",];
        }
        return $data;
    }

    public function getSelectedBank($id)
    {
        $bank = Bank::findOrFail($id);
        return ['id' => $bank->id, 'text' => $bank->name];
    }
}
