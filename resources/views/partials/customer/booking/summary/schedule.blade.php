@php
    /**
     * @var \App\Models\Booking\Booking $booking
     */
    $travellerCount = $booking->traveller_count; // Stored to reduce query count
@endphp
<x-customer.accordion id="schedule-collapse" nobg>
    <x-slot:header>
        <h2 class="col-md-12 mb-0">Payment Schedule</h2>
    </x-slot:header>

    <table class="table table-striped text-center table-mobile-sided">
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
            <td data-content="Description">Due with Order</td>
            <td data-content="Cost">{{ f_currency($booking->tour->deposit) }}</td>
            <td data-content="Quantity">{{ $travellerCount }}</td>
            <td data-content="Instalment total">{{ f_currency($booking->tour->deposit * $travellerCount) }}</td>
            <td data-content="Total Owed">{{ f_currency($booking->tour->deposit * $travellerCount) }}</td>
        </tr>
        @php
            $cumulative = $booking->tour->deposit * $travellerCount;
        @endphp
        @foreach($booking->tour->paymentInstallments as $installment)
            @php $cumulative += ($installment->cost * $travellerCount) @endphp
            <tr @if ($installment->due_on->lt(now())) style="text-decoration: underline black;" @endif>
                <td data-content="Description">
                    {{ f_date($installment->due_on) }}

                </td>
                <td data-content="Cost">{{ f_currency($installment->cost) }}</td>
                <td data-content="Quantity">{{ $travellerCount }}</td>
                <td data-content="Instalment total">{{ f_currency($installment->cost * $travellerCount) }}</td>
                <td data-content="Total Owed">{{ f_currency($cumulative) }}</td>
            </tr>
        @endforeach
        @php $cumulative += ($booking->tour->remaining_installment * $travellerCount) @endphp
        <tr>
            <td data-content="Description">{{ f_date($booking->tour->final_payment) }}</td>
            <td data-content="Cost">{{ f_currency($booking->tour->remaining_installment) }}</td>
            <td data-content="Quantity">{{ $travellerCount }}</td>
            <td data-content="Instalment total">{{ f_currency($booking->tour->remaining_installment * $travellerCount) }}</td>
            <td data-content="Total Owed">{{ f_currency($cumulative) }}</td>
        </tr>
        </tbody>
    </table>
</x-customer.accordion>
