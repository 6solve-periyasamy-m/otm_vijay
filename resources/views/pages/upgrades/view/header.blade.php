<div class="otm-callout">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold">{{ $tour->name }}</h4>
        </div>
        <div class="col-12 col-xl-6">
            <p>Event</p>
            <h6 class="fw-bold">{{ isset($tour->event) ? $tour->event->name : "None" }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Booking URL</p>
            <h6 class="fw-bold">
                @if(!empty($tour->booking_form_url))
                    <a href="{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}">{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}</a>
                @else
                    No Booking URL Set
                @endif
            </h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Price per Person</p>
            <h6 class="fw-bold">{{ $tour->base_price_per_person }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Single Occupancy Surcharge</p>
            <h6 class="fw-bold">{{ $tour->single_occupancy_surcharge }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>From</p>
            <h6 class="fw-bold">{{ f_date($tour->date_from) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>To</p>
            <h6 class="fw-bold">{{ f_date($tour->date_to) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Margin</p>
            <h6 class="fw-bold">{{ $tour->margin }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Is Active</p>
            <h6 class="fw-bold">{{ $tour->is_active ? "Yes" : "No" }}</h6>
        </div>
        <div class="col-12 col-xl-12">
            <p>Notes</p>
            <h6 class="fw-bold">{{ $tour->notes }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Description</p>
            <h6 class="fw-bold">{{ $tour->description }}</h6>
        </div>
    </div>
    <a href="{{ route('tours.view', ['tour' => $tour, ])}}" class="btn btn-primary text-white">
        <x-icon icon="arrow-left" />
        Back to Tour
    </a>
</div>
<hr class="splitter"/>
