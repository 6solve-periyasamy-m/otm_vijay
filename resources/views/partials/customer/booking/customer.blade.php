@php /** @var \App\Models\Booking\BookingTraveller $traveller */ @endphp

<div class="customer-card" wire:click="changeActive({{$traveller->id}})">
    <div class="d-block fs-20 text-center border-bottom p-1">
        {{ $traveller->full_name }}
    </div>
    <div class="d-block border-bottom">
        <div class="d-flex flex-between">
            <div class="w-100 text-center px-1">
                <span class="d-block fw-bold">Base</span>
                <span class="d-block">{{ f_currency($traveller->base_cost) }}</span>
            </div>
            @if($traveller->additional_cost > 0)
            <div class="w-100 text-center border-left px-1">
                <span class="d-block fw-bold">Additional</span>
                <span class="d-block">{{ f_currency($traveller->additional_cost) }}</span>
            </div>
            @endif
            @if($traveller->surcharge_amount > 0)
            <div class="w-100 text-center border-left px-1">
                <span class="d-block fw-bold">Surcharge</span>
                <span class="d-block">{{ f_currency($traveller->surcharge_amount) }}</span>
            </div>
            @endif
            @if($traveller->base_cost !== $traveller->total_cost)
            <div class="w-100 text-center border-left px-1">
                <span class="d-block fw-bold">Total</span>
                <span class="d-block">{{ f_currency($traveller->total_cost) }}</span>
            </div>
            @endif
        </div>
    </div>
    <div class="d-block">

            @if($traveller->booking->lead_traveller_id === $traveller->id)
            <div class="flex-centered fs-14">
                Lead Booker
            </div>
            @else
            <div class="d-flex flex-between">
                <div class="w-100 text-center p-1">
                    <button class="btn btn-warning text-dark" style="width: 4rem;" wire:click="$emit('openModal', 'customer.booking.form.additional-traveller', {{ json_encode(['booking' => $traveller->booking->id, 'traveller' => $traveller->id]) }})">Edit</button>
                </div>
                <div class="text-center p-1 border-left w-100">
                    <button class="btn btn-danger text-dark" style="width: 4rem;" wire:click="$emit('openModal', 'customer.booking.form.additional-traveller', {{ json_encode(['booking' => $traveller->booking->id, 'traveller' => $traveller->id]) }})">Delete</button>
                </div>
            </div>
            @endif
    </div>
</div>
