@php /** @var \App\Models\Booking\Booking $booking */ @endphp
<x-customer.accordion id="travellers-collapse" nobg>
    <x-slot:header>
        <h2 class="mb-0">Travellers</h2>
    </x-slot:header>
    Room assignments may differ slightly if incorrect group sizes were provided.
    <hr class="splitter">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th scope="col">Name</th>
            @if($shouldRooming)
            <th scope="col">Room</th>
            @endif
            <th scope="col">Base Cost</th>
            <th scope="col">Additional Costs</th>
            @if($shouldRooming)
                <th scope="col">Single Occupancy Surcharge</th>
            @endif
            <th scope="col">Total Cost for Traveller</th>
        </tr>
        </thead>
        <tbody>
        @foreach($booking->travellers as $traveller)
            <tr wire:click="changeActive({{$traveller->id}})">
                <td data-content="Name">{{ $traveller->first_name . ' ' . $traveller->last_name }} @if($traveller->id === $active->id)(Active)@endif</td>
                @if($shouldRooming)
                    <td data-content="Room">{{ $traveller->primary_group?->name }} ({{ $traveller->roomType->name }})</td>
                @endif
                <td data-content="Base Cost">{{ f_currency($traveller->base_cost) }}</td>
                <td data-content="Additional Costs">{{ f_currency($traveller->additional_cost) }}</td>
                @if($shouldRooming)
                    <td data-content="Single Occupancy Surcharge">{{ f_currency($traveller->surcharge_amount) }}</td>
                @endif
                <td data-content="Total Cost for Traveller">{{ f_currency($traveller->total_cost) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ route('customer-booking.index', ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token]) }}"
       class="btn btn-info text-white float-end" onclick="return confirm('Warning: Editing order details will clear add-ons/upgrades. Continue?');">Edit Order Details</a>
</x-customer.accordion>
