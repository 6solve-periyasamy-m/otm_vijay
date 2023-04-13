<div>
    <div class="mb-4">
        <x-customer.expander id="travellers" nobg>
            <x-slot:header>
                <h2 class="mb-0">All Travellers</h2>
            </x-slot:header>
            @foreach($this->booking->travellers as $traveller)
                @include('partials.customer.booking.customer', ['traveller' => $traveller])
            @endforeach
        </x-customer.expander>
    </div>

    <livewire:customer.booking.traveller-components key="{{ now() }}" :traveller="$this->active"/>

    @include('partials.customer.booking.summary.schedule', ['booking' => $this->booking,])

    <livewire:customer.booking.payment :booking="$this->booking" />
    <x-wire-loader />
</div>
