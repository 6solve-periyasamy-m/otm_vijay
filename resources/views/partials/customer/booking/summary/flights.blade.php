@php /** @var \App\Models\Booking\BookingTraveller $traveller */ @endphp
<x-customer.accordion id="flights-{{ $traveller->id }}-collapse" nobg>
    <x-slot:header>
        <h2 class="col-md-12 mb-0">Flights</h2>
    </x-slot:header>
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th scope="col">Times</th>
            <th scope="col">Description</th>
            <th scope="col">Direction</th>
            <th scope="col">Type</th>
            <th scope="col">Cost</th>
        </tr>
        </thead>
        <tbody>
        @foreach($traveller->flights()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.flight', 'tourComponent.inventory.flight.departureAirport', 'tourComponent.inventory.flight.arrivalAirport', 'tourComponent.inventory.flight.departureAirport.address', 'tourComponent.inventory.flight.arrivalAirport.address')->get() as $component)
            <tr>
                <td data-content="Times">
                    {{ f_datetime($component->tourComponent->inventory->departs_at) . ' to ' . f_datetime($component->tourComponent->inventory->arrives_at) }}
                </td>
                <td data-content="Description">
                    {{ $component->tourComponent->inventory->flight->departureAirport }}
                    to
                    {{ $component->tourComponent->inventory->flight->arrivalAirport }}
                    ({{ $component->tourComponent->inventory->travelClass }})
                </td>
                <td data-content="Direction">
                    {{ $component->tourComponent->flight_type }}
                </td>
                @if($component->tourComponent->tour_component_type == 'Included')
                    <td colspan="2" data-content="Type">
                        {{ $component->tourComponent->tour_component_type }}
                    </td>
                @else
                    <td data-content="Type">
                        {{ $component->tourComponent->tour_component_type }}
                    </td>
                    <td data-content="Cost">
                        {{ f_currency($component->tourComponent->tour_sales_price) }}
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</x-customer.accordion>
