@php
    /**
     * @var \App\Models\Booking\Booking $booking
     */
    $travellerCount = $booking->traveller_count; // Stored to reduce query count
@endphp
<div class="card">
    <div class="card-body">
        <h2 class="col-md-12 mb-0">Payment Schedule</h2>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th scope="col">Description</th>
                <th scope="col">Cost</th>
                <th scope="col">Quantity</th>
                <th scope="col">Instalment total</th>
                <th scope="col">Total Owed</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Due with Order</td>
                <td>{{ f_currency($booking->tour->deposit) }}</td>
                <td>{{ $travellerCount }}</td>
                <td>{{ f_currency($booking->tour->deposit * $travellerCount) }}</td>
                <td>{{ f_currency($booking->tour->deposit * $travellerCount) }}</td>
            </tr>
            @php
                $cumulative = $booking->tour->deposit * $travellerCount;
            @endphp
            @foreach($booking->tour->paymentInstallments as $installment)
                @php $cumulative += ($installment->cost * $travellerCount) @endphp
                <tr @if ($installment->due_on->lt(now())) style="text-decoration: underline black;" @endif>
                    <td>
                        {{ f_date($installment->due_on) }}

                    </td>
                    <td>{{ f_currency($installment->cost) }}</td>
                    <td>{{ $travellerCount }}</td>
                    <td>{{ f_currency($installment->cost * $travellerCount) }}</td>
                    <td>{{ f_currency($cumulative) }}</td>
                </tr>
            @endforeach
            @php $cumulative += ($booking->tour->remaining_installment * $travellerCount) @endphp
            <tr>
                <td>{{ f_date($booking->tour->final_payment) }}</td>
                <td>{{ f_currency($booking->tour->remaining_installment) }}</td>
                <td>{{ $travellerCount }}</td>
                <td>{{ f_currency($booking->tour->remaining_installment * $travellerCount) }}</td>
                <td>{{ f_currency($cumulative) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
