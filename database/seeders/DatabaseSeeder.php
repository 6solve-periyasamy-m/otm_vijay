<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);

        if (config('app.debug')) {
            $this->call(CountriesTableSeeder::class);
            $this->call(CurrenciesTableSeeder::class);
            $this->call(CountryCurrenciesTableSeeder::class);

            $this->call(LocationTypesTableSeeder::class);
            $this->call(AddressesTableSeeder::class);
            $this->call(AirlinesTableSeeder::class);
            $this->call(AirportsTableSeeder::class);
            $this->call(BoardTypesTableSeeder::class);
            $this->call(ActivityTypesTableSeeder::class);
            $this->call(EventsTableSeeder::class);
            $this->call(OperatorsTableSeeder::class);
            $this->call(VariantsTableSeeder::class);
            $this->call(MerchandiseSizesTableSeeder::class);
            $this->call(MerchandiseTypesTableSeeder::class);
            $this->call(CustomersTableSeeder::class);

            $this->call(TourCategoriesTableSeeder::class);
            $this->call(ToursTableSeeder::class);
            $this->call(TransportTypesTableSeeder::class);
            $this->call(TravelClassesTableSeeder::class);
            $this->call(RoomTypesTableSeeder::class);
            $this->call(TicketTypesTableSeeder::class);

            $this->call(AccommodationsTableSeeder::class);
            $this->call(AccommodationInventoriesTableSeeder::class);
            $this->call(AccommodationInventoryToursTableSeeder::class);
            $this->call(AccommodationInventoryTourUpgradesTableSeeder::class);

            $this->call(ActivitiesTableSeeder::class);
            $this->call(ActivityInventoriesTableSeeder::class);
            $this->call(ActivityInventoryToursTableSeeder::class);
            $this->call(ActivityInventoryTourUpgradesTableSeeder::class);

            $this->call(FlightsTableSeeder::class);
            $this->call(FlightInventoriesTableSeeder::class);
            $this->call(FlightInventoryToursTableSeeder::class);
            $this->call(FlightInventoryTourUpgradesTableSeeder::class);

            $this->call(TransportsTableSeeder::class);
            $this->call(TransportInventoriesTableSeeder::class);
            $this->call(TransportInventoryToursTableSeeder::class);
            $this->call(TransportInventoryTourUpgradesTableSeeder::class);

            $this->call(MerchandisesTableSeeder::class);
            $this->call(MerchandiseInventoriesTableSeeder::class);
            $this->call(MerchandiseInventoryToursTableSeeder::class);

            $this->call(OrdersTableSeeder::class);
            $this->call(ManualAdjustmentsTableSeeder::class);
            $this->call(OrderCustomerAdjustmentsTableSeeder::class);
            $this->call(GroupsTableSeeder::class);
            $this->call(OrderCustomerGroupTableSeeder::class);
            $this->call(OrderAccommodationsTableSeeder::class);
            $this->call(OrderActivitiesTableSeeder::class);
            $this->call(OrderFlightsTableSeeder::class);
            $this->call(OrderTransportsTableSeeder::class);
            $this->call(OrderMerchandisesTableSeeder::class);
            $this->call(PaymentInstallmentsTableSeeder::class);
            $this->call(PaymentsTableSeeder::class);
            $this->call(OrderInstallmentsTableSeeder::class);

            $this->call(QuotesTableSeeder::class);
            $this->call(QuoteActivitiesTableSeeder::class);
            $this->call(QuoteFlightsTableSeeder::class);
            $this->call(QuoteInstallmentsTableSeeder::class);
            $this->call(QuoteMerchandisesTableSeeder::class);
            $this->call(QuotePricePointsTableSeeder::class);
            $this->call(QuoteTransportsTableSeeder::class);

            $this->call(SuppliersTableSeeder::class);
            $this->call(SupplierAssociatesTableSeeder::class);
        } else {
            Artisan::call('countries:update');
        }
    }
}
