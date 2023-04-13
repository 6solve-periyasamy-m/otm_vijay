<div>
    <div class="mb-4">
        <x-customer.expander id="travellers" nobg>
            <x-slot:header>
                <h2 class="mb-0">All Travellers</h2>
            </x-slot:header>
            @foreach($this->booking->travellers as $traveller)
                @include('partials.customer.booking.customer', ['traveller' => $traveller])
            @endforeach
            <div class="customer-card" wire:click="$emit('openModal', 'customer.booking.form.additional-traveller', {{ json_encode(['booking' => $this->booking->id,]) }})">
                <div class="flex-centered fs-30">
                    <i class="icon-plus"></i>
                </div>
            </div>
        </x-customer.expander>
    </div>

    <livewire:customer.booking.traveller-components key="{{ now() }}" :traveller="$this->active"/>

    @include('partials.customer.booking.summary.schedule', ['booking' => $this->booking,])

    <livewire:customer.booking.payment key="{{ $this->booking->due_today }}" :booking="$this->booking" />
    <x-wire-loader />
</div>
