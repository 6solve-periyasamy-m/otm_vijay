<?php

use App\Http\Controllers\Api\SelectController;
use App\View\Components\Livewire\Input\Select\AccommodationInventory;
use App\View\Components\Livewire\Input\Select\Country;
use App\View\Components\Livewire\Input\Select\Currency;
use App\View\Components\Livewire\Input\Select\Customer;
use App\View\Components\Livewire\Input\Select\Event\All as AllEvents;
use App\View\Components\Livewire\Input\Select\Event\Main as MainEvent;
use App\View\Components\Livewire\Input\Select\Event\Normal as NormalEvent;
use App\View\Components\Livewire\Input\Select\Organization;
use App\View\Components\Livewire\Input\Select\TaxBracket;
use App\View\Components\Livewire\Input\Select\User;

Route::prefix('organizations')->name('organizations.')->group(function () {
    Route::post('/', [Organization::class, 'getAll'])->name('select');
    Route::post('/{id}', [Organization::class, 'getOne'])->name('selected');
});

Route::prefix('users')->name('users.')->group(function () {
    Route::post('/', [User::class, 'getAll'])->name('select');
    Route::post('/{id}', [User::class, 'getOne'])->name('selected');
});

Route::prefix('tax-brackets')->name('tax-brackets.')->group(function () {
    Route::post('/', [TaxBracket::class, 'getAll'])->name('select');
    Route::post('/{id}', [TaxBracket::class, 'getOne'])->name('selected');
});

Route::prefix('currencies')->name('currencies.')->group(function () {
    Route::post('/', [Currency::class, 'getAll'])->name('select');
    Route::post('/{id}', [Country::class, 'getOne'])->name('selected');
});

Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::prefix('accommodation')->name('accommodation.')->group(function () {
        Route::post('/', [AccommodationInventory::class, 'getAll'])->name('select');
        Route::post('/{id}', [AccommodationInventory::class, 'getOne'])->name('selected');
    });
});

Route::prefix('customers')->name('customers.')->group(function () {
    Route::post('/', [Customer::class, 'getAll'])->name('select');
    Route::post('/{id}', [Customer::class, 'getOne'])->name('selected');
});

Route::prefix('events')->name('events.')->group(function () {
    Route::prefix('main')->name('main.')->group(function () {
        Route::post('/', [MainEvent::class, 'getAll'])->name('select');
        Route::post('/{id}', [MainEvent::class, 'getOne'])->name('selected');
    });
    Route::prefix('normal')->name('main.')->group(function () {
        Route::post('/', [NormalEvent::class, 'getAll'])->name('select');
        Route::post('/{id}', [NormalEvent::class, 'getOne'])->name('selected');
    });
    Route::post('/', [AllEvents::class, 'getAll'])->name('select');
    Route::post('/{id}', [AllEvents::class, 'getOne'])->name('selected');
});

Route::post('locations', [SelectController::class, 'getLocations'])->name('locations.select');
Route::post('addresses', [SelectController::class, 'getAddresses'])->name('addresses.select');
Route::post('filter/countries', [SelectController::class, 'getAvailableFilterCountries'])->name('countries.filter.select');
Route::post('regions', [SelectController::class, 'getRegions'])->name('regions.select');
Route::post('location-types', [SelectController::class, 'getLocationTypes'])->name('location-types.select');
Route::post('room-types', [SelectController::class, 'getRoomTypes'])->name('room-types.select');
Route::post('board-types', [SelectController::class, 'getBoardTypes'])->name('board-types.select');
Route::post('transport-types', [SelectController::class, 'getTransportTypes'])->name('transport-types.select');
Route::post('operators', [SelectController::class, 'getOperators'])->name('operators.select');
Route::post('travel-classes', [SelectController::class, 'getTravelClasses'])->name('travel-classes.select');
Route::post('activity-types', [SelectController::class, 'getActivityTypes'])->name('activity-types.select');
Route::post('ticket-types', [SelectController::class, 'getTicketTypes'])->name('ticket-types.select');
Route::post('tours', [SelectController::class, 'getTours'])->name('tours.select');
Route::post('airports', [SelectController::class, 'getAirports'])->name('airports.select');
Route::post('airlines', [SelectController::class, 'getAirlines'])->name('airlines.select');
Route::post('quotes', [SelectController::class, 'getQuotes'])->name('quotes.select');
Route::post('customer/{order}', [SelectController::class, 'getAvailableCustomers'])->name('available-customers.select');
Route::post('payment-method', [SelectController::class, 'getPaymentMethods'])->name('payment-method.select');
Route::post('tour-category', [SelectController::class, 'getTourCategories'])->name('tour-categories.select');
Route::post('merchandise-types', [SelectController::class, 'getAvailableMerchandiseTypes'])->name('merchandise-types.select');
Route::post('variants', [SelectController::class, 'getAvailableVariants'])->name('variants.select');
Route::post('sizes', [SelectController::class, 'getAvailableSizes'])->name('sizes.select');
Route::post('brands', [SelectController::class, 'getAvailableBrands'])->name('brands.select');
Route::post('banks', [SelectController::class, 'getAvailableBanks'])->name('banks.select');
Route::prefix('inventory')->group(function () {
    Route::post('activity', [SelectController::class, 'getActivityInventory'])->name('inventory.activity.select');
    Route::post('flight', [SelectController::class, 'getFlightInventory'])->name('inventory.flight.select');
    Route::post('transport', [SelectController::class, 'getTransportInventory'])->name('inventory.transport.select');
    Route::prefix('{inventoryTour}')->group(function () {
        Route::post('accommodation', [SelectController::class, 'getAccommodationInventoryForUpgrade'])->name('inventory.accommodation.upgrade.select');
        Route::post('activity', [SelectController::class, 'getActivityInventoryForUpgrade'])->name('inventory.activity.upgrade.select');
        Route::post('flight', [SelectController::class, 'getFlightInventoryForUpgrade'])->name('inventory.flight.upgrade.select');
        Route::post('transport', [SelectController::class, 'getTransportInventoryForUpgrade'])->name('inventory.transport.upgrade.select');
    });
});
Route::prefix('selected')->group(function () {
    Route::post('filter/countries/{id}', [SelectController::class, 'getSelectedFilterCountries'])->name('countries.filter.selected');
    Route::post('location/{id}', [SelectController::class, 'getSelectedLocation'])->name('locations.selected');
    Route::post('address/{id}', [SelectController::class, 'getSelectedAddress'])->name('addresses.selected');
    Route::post('currency/{id}', [SelectController::class, 'getSelectedCurrency'])->name('currencies.selected');
    Route::post('region/{id}', [SelectController::class, 'getSelectedRegion'])->name('regions.selected');
    Route::post('location-type/{id}', [SelectController::class, 'getSelectedLocationType'])->name('location-types.selected');
    Route::post('room-type/{id}', [SelectController::class, 'getSelectedRoomType'])->name('room-types.selected');
    Route::post('board-type/{id}', [SelectController::class, 'getSelectedBoardType'])->name('board-types.selected');
    Route::post('transport-type/{id}', [SelectController::class, 'getSelectedTransportType'])->name('transport-types.selected');
    Route::post('operator/{id}', [SelectController::class, 'getSelectedOperator'])->name('operators.selected');
    Route::post('travel-class/{id}', [SelectController::class, 'getSelectedTravelClass'])->name('travel-classes.selected');
    Route::post('activity-type/{id}', [SelectController::class, 'getSelectedActivityType'])->name('activity-types.selected');
    Route::post('ticket-type/{id}', [SelectController::class, 'getSelectedTicketTypes'])->name('ticket-types.selected');
    Route::post('tour/{id}', [SelectController::class, 'getSelectedTour'])->name('tours.selected');
    Route::post('airports/{id}', [SelectController::class, 'getSelectedAirport'])->name('airports.selected');
    Route::post('airlines/{id}', [SelectController::class, 'getSelectedAirline'])->name('airlines.selected');
    Route::post('quotes/{id}', [SelectController::class, 'getSelectedQuote'])->name('quotes.selected');
    Route::post('payment-method/{id}', [SelectController::class, 'getSelectedPaymentMethod'])->name('payment-method.selected');
    Route::post('tour-category/{id}', [SelectController::class, 'getSelectedTourCategory'])->name('tour-categories.selected');
    Route::post('merchandise-types/{id}', [SelectController::class, 'getSelectedMerchandiseType'])->name('merchandise-types.selected');
    Route::post('variants/{id}', [SelectController::class, 'getSelectedVariant'])->name('variants.selected');
    Route::post('sizes/{id}', [SelectController::class, 'getSelectedSize'])->name('sizes.selected');
    Route::post('brands/{id}', [SelectController::class, 'getSelectedBrand'])->name('brands.selected');
    Route::post('banks/{id}', [SelectController::class, 'getSelectedBank'])->name('banks.selected');
    Route::prefix('inventory/{id}')->group(function () {
        Route::post('activity', [SelectController::class, 'getSelectedActivityInventory'])->name('inventory.activity.selected');
        Route::post('flight', [SelectController::class, 'getSelectedFlightInventory'])->name('inventory.flight.selected');
        Route::post('transport', [SelectController::class, 'getSelectedTransportInventory'])->name('inventory.transport.selected');
        Route::prefix('upgrade')->group(function () {
            Route::post('accommodation', [SelectController::class, 'getSelectedAccommodationInventoryForUpgrade'])->name('inventory.accommodation.upgrade.selected');
            Route::post('activity', [SelectController::class, 'getSelectedActivityInventoryForUpgrade'])->name('inventory.activity.upgrade.selected');
            Route::post('flight', [SelectController::class, 'getSelectedFlightInventoryForUpgrade'])->name('inventory.flight.upgrade.selected');
            Route::post('transport', [SelectController::class, 'getSelectedTransportInventoryForUpgrade'])->name('inventory.transport.upgrade.selected');
        });
    });
});
