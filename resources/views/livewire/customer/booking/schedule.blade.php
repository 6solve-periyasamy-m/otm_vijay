@php
    $travellerCount = $this->booking->traveller_count; // Stored to reduce query count
@endphp
<x-customer.accordion id="schedule-collapse" nobg>
    <x-slot:header>
        <h2 class="mb-0" style="width: 100%; text-align: center;">Payment Schedule</h2>
    </x-slot:header>

    @if(flag('installments.force', false))
        <div class="fw-bold">
            If there are past dated instalments in the schedule you will be expected to pay these in addition to the deposit in order to secure your booking.
        </div>
    @endif

    <table class="table table-striped text-center table-mobile-sided">
        <thead>
        <tr>
            <th scope="col">Description</th>
            <th scope="col">Cost</th>
            <th scope="col">Quantity</th>
            <th scope="col">Instalment total</th>
            <th scope="col">Total Owed</th>
            @if(flag('installments.force', false))
                <th scope="col">Due Today</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <tr>
            <td data-content="Description">Due with Order</td>
            <td data-content="Cost">{{ f_currency($this->booking->tour?->deposit_amount) }}</td>
            <td data-content="Quantity">{{ $travellerCount }}</td>
            <td data-content="Instalment total">{{ f_currency($this->booking->tour?->deposit_amount * $travellerCount) }}</td>
            <td data-content="Total Owed">{{ f_currency($this->booking->tour?->deposit_amount * $travellerCount) }}</td>
            @if(flag('installments.force', false))
                <td data-content="Total Owed">Yes</td>
            @endif
        </tr>
        @php
            $cumulative = $this->booking->tour?->deposit_amount * $travellerCount;
        @endphp
        @foreach($this->booking->tour?->paymentInstallments as $installment)
            @php $cumulative += ($installment->cost * $travellerCount) @endphp
            <tr @if ($installment->due_on->lt(now())) style="text-decoration: underline #000000;" @endif>
                <td data-content="Description">{{ f_date($installment->due_on) }}</td>
                <td data-content="Cost">{{ f_currency($installment->cost) }}</td>
                <td data-content="Quantity">{{ $travellerCount }}</td>
                <td data-content="Instalment total">{{ f_currency($installment->cost * $travellerCount) }}</td>
                <td data-content="Total Owed">{{ f_currency($cumulative) }}</td>
                @if(flag('installments.force', false))
                    <td data-content="Total Owed">{{ f_bool($installment->due_on->isBefore(now())) }}</td>
                @endif
            </tr>
        @endforeach
        @php $remaining = $this->booking->repository->getRemainingInstallmentAmount(); $cumulative += ($remaining) @endphp
        <tr>
            <td data-content="Description">{{ f_date($this->booking->tour?->final_payment) }}</td>
            <td data-content="Cost">-</td>
            <td data-content="Quantity">-</td>
            <td data-content="Instalment total">{{ f_currency($remaining) }}</td>
            <td data-content="Total Owed">{{ f_currency($cumulative) }}</td>
            @if(flag('installments.force', false))
                <td data-content="Total Owed">{{ f_bool($this->booking->tour?->final_payment->isBefore(now())) }}</td>
            @endif
        </tr>
        </tbody>
    </table>
</x-customer.accordion>
