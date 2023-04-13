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
        <div class="d-flex flex-between">
            <div class="w-100 text-center px-1">
                <span class="d-block fw-bold">Room</span>
                <span class="d-block">{{ $traveller->primary_group?->name }}</span>
            </div>
            <div class="w-100 text-center border-left px-1">
                <span class="d-block fw-bold">Type</span>
                <span class="d-block">{{ $traveller->roomType->name }}</span>
            </div>
        </div>
    </div>
</div>
