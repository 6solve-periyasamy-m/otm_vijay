<?php

namespace App\Imports;

use App\Models\Currency;
use App\Models\Flight\Airline;
use App\Models\Flight\Airport;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventory;
use App\Models\TravelClass;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class FlightInventoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return FlightInventory|null
     */
    public function model(array $row)
    {
        $airline = Airline::firstOrCreate($row[0]);
        $departure = Airport::where('name', 'like', $row[1])->first();
        $arrival = Airport::where('name', 'like', $row[2])->first();
        $isDomestic = $row[3] == 'YES';
        $currency = Currency::where('code', '=', $row[4])->first();
        if (!isset($departure) || !isset($arrival)) { return null; }
        $flight = Flight::firstOrCreate($airline, $departure, $arrival, $isDomestic, $currency, $row[5]);
        return new FlightInventory([
            'flight_id' => $flight->id,
            'flight_number' => trim($row[6]),
            'travel_class_id' => TravelClass::firstOrCreate(trim($row[7]))->id,
            'check_in' => Carbon::createFromFormat('d/m/Y H:i', trim($row[8])),
            'departs_at' => Carbon::createFromFormat('d/m/Y H:i', trim($row[9])),
            'arrives_at' => Carbon::createFromFormat('d/m/Y H:i', trim($row[10])),
            'fit_selectable' => trim($row[11]) == 'YES',
            'stock' => trim($row[12]),
            'purchase_price' => trim($row[13]),
            'sales_price' => trim($row[14]) != '' ? trim($row[14]) : trim($row[13]),
            'notes' => trim($row[15]),
        ]);
    }
}
