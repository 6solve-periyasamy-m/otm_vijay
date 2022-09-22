@php
    /**
     * @var \App\Models\Booking\Booking $booking
     */
    $travellerCount = $booking->traveller_count; // Stored to reduce query count
@endphp
<x-customer.accordion id="cost-collapse" nobg hide>
    <x-slot:header>
        <h2 class="col-md-12 mb-0">Cost Summary</h2>
    </x-slot:header>
    <table class="table table-striped text-center table-mobile-sided">
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
            <td data-content="Description">Base Cost</td>
            <td data-content="Cost">{{ f_currency($booking->tour->base_price_per_person) }}</td>
            <td data-content="Quantity">{{ $travellerCount }}</td>
            <td data-content="Total">{{ f_currency($booking->tour->base_price_per_person * $travellerCount) }}</td>
        </tr>
        @if($booking->leadTraveller->additional_cost > 0)
            <tr>
                <td data-content="Description">Additional Costs (As Above)</td>
                <td data-content="Cost">{{ f_currency($booking->leadTraveller->additional_cost) }}</td>
                <td data-content="Quantity">{{ $travellerCount }}</td>
                <td data-content="Total">{{ f_currency($booking->leadTraveller->additional_cost * $travellerCount) }}</td>
            </tr>
        @endif
        @if($booking->repository->getSingleOccupancyCount() > 0)
            <tr>
                <td data-content="Description">Single Occupancy Surcharge</td>
                <td data-content="Cost">{{ f_currency($booking->tour->single_occupancy_surcharge)}}</td>
                <td data-content="Quantity">{{ $booking->repository->getSingleOccupancyCount() }}</td>
                <td data-content="Total">{{ f_currency($booking->tour->single_occupancy_surcharge * $booking->repository->getSingleOccupancyCount()) }}</td>
            </tr>
        @endif
        <tr>
            <td colspan="3" data-content="Description">Total Cost</td>
            <td data-content="Cost">{{ f_currency($booking->repository->getTotalCost()) }}</td>
        </tr>
        <tr>
            <td data-content="Description">Deposit (Due Today)</td>
            <td data-content="Cost">{{ f_currency($booking->tour->deposit) }}</td>
            <td data-content="Quantity">{{ $travellerCount }}</td>
            <td data-content="Total">{{ f_currency($booking->tour->deposit * $travellerCount) }}</td>
        </tr>
        </tbody>
    </table>
</x-customer.accordion>
