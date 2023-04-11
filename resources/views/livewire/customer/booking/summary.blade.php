<div>
    @include('partials.customer.booking.summary.travellers', ['booking' => $this->booking, 'shouldRooming' => $this->booking->tour->templates->count() > 0])

    <livewire:customer.booking.traveller-components :traveller="$this->active"/>

    @include('partials.customer.booking.summary.schedule', ['booking' => $this->booking,])
    
    @if(!$this->accepted)
        <x-customer.accordion id="cost-collapse" nobg>
            <x-slot:header>
                <h2 class="col-md-12 mb-0">Terms and Conditions</h2>
            </x-slot:header>
            {!! $this->booking->tour->terms !!}
            <br />
            <button wire:click="accept" class="btn btn-success">Accept the Terms and Conditions</button>
        </x-customer.accordion>
    @else
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Make Payment</h2>
            </div>
        </div>
        @if(\Gateway::getDefaultGateway() !== null)
            <div class="card hidden">
                <div class="card-body">
                    <form class="form-material" action="{{ route('customer-booking.deposit', ['bookingUrl' => $this->booking->tour->booking_form_url, 'token' => $this->booking->token]) }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="booking_reference" id="form-booking-reference">
                        <div class="form-material row">
                            <x-customer.input name="amount" value="{{ $this->booking->due_today }}" width="10" required>
                                How much do you want to pay today?
                            </x-customer.input>
                            <div class="form-group col-12 col-xl-2" style="padding-top: 19px;">
                                <input class="btn btn-primary text-white" type="submit" value="Make Payment">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card hidden">
                <div class="card-body">
                    <h2 class="col-md-12 mb-0">The operator has not enabled online payments</h2>
                </div>
            </div>
        @endif
    @endif
</div>
