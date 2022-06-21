@php
    /**
     * @var \App\Models\Booking\Booking $booking
     */
    $travellerCount = $booking->traveller_count; // Stored to reduce query count
@endphp
<x-customer.accordion id="cost-collapse" nobg>
    <x-slot:header>
        <h2 class="col-md-12 mb-0">Cost Summary</h2>
    </x-slot:header>
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th scope="col">Description</th>
            <th scope="col">Cost</th>
            <th scope="col">Quantity</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Base Cost</td>
            <td>{{ f_currency($booking->tour->base_price_per_person) }}</td>
            <td>{{ $travellerCount }}</td>
            <td>{{ f_currency($booking->tour->base_price_per_person * $travellerCount) }}</td>
        </tr>
        @if($booking->leadTraveller->additional_cost > 0)
            <tr>
                <td>Additional Costs (As Above)</td>
                <td>{{ f_currency($booking->leadTraveller->additional_cost) }}</td>
                <td>{{ $travellerCount }}</td>
                <td>{{ f_currency($booking->leadTraveller->additional_cost * $travellerCount) }}</td>
            </tr>
        @endif
        @if($booking->repository->getSingleOccupancyCount() > 0)
            <tr>
                <td>Single Occupancy Surcharge</td>
                <td>{{ f_currency($booking->tour->single_occupancy_surcharge)}}</td>
                <td>{{ $booking->repository->getSingleOccupancyCount() }}</td>
                <td>{{ f_currency($booking->tour->single_occupancy_surcharge * $booking->repository->getSingleOccupancyCount()) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="3">Total Cost</td>
            <td>{{ f_currency($booking->repository->getTotalCost()) }}</td>
        </tr>
        <tr>
            <td>Deposit (Due Today)</td>
            <td>{{ f_currency($booking->tour->deposit) }}</td>
            <td>{{ $travellerCount }}</td>
            <td>{{ f_currency($booking->tour->deposit * $travellerCount) }}</td>
        </tr>
        </tbody>
    </table>
</x-customer.accordion>
