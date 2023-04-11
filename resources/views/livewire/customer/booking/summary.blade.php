<div>
    @include('partials.customer.booking.summary.travellers', ['booking' => $this->booking, 'active' => $this->active, 'shouldRooming' => $this->booking->tour->templates->count() > 0])

    <livewire:customer.booking.traveller-components key="{{ now() }}" :traveller="$this->active"/>

    @include('partials.customer.booking.summary.schedule', ['booking' => $this->booking,])

    <livewire:customer.booking.payment :booking="$this->booking" />
    <x-wire-loader />
</div>
