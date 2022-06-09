@php /** @var \App\Models\Booking\Booking $booking */ @endphp
<div class="card">
    <div class="card-body">
        <h2 class="col-md-12 mb-0">Travellers</h2>
    </div>
</div>
<div class="card">
    <div class="card-body">
        Room assignments may differ slightly if incorrect group sizes were provided.
        <hr class="splitter">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Room</th>
                <th scope="col">Base Cost</th>
                <th scope="col">Additional Costs</th>
                <th scope="col">Single Occupancy Surcharge</th>
                <th scope="col">Total Cost for Traveller</th>
            </tr>
            </thead>
            <tbody>
            @foreach($booking->travellers as $traveller)
                <tr>
                    <td>{{ $traveller->first_name . ' ' . $traveller->last_name }}</td>
                    <td>{{ $traveller->primary_group?->name }} ({{ $traveller->roomType->name }})</td>
                    <td>{{ f_currency($traveller->base_cost) }}</td>
                    <td>{{ f_currency($traveller->additional_cost) }}</td>
                    <td>{{ f_currency($traveller->surcharge_amount) }}</td>
                    <td>{{ f_currency($traveller->total_cost) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a href="{{ route('customer-booking.index', ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token]) }}"
           class="btn btn-info text-white float-end" onclick="return confirm('Warning: Editing order details will clear add-ons/upgrades. Continue?');">Edit Order Details</a>
    </div>
</div>