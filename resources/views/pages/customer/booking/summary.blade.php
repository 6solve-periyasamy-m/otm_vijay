@extends('pages.customer.booking.layout')

@php
/**
 * @var \App\Models\Booking\Booking $booking
 */
$lead = $booking->leadTraveller;
$leadAddons = $booking->leadTraveller->repository->getAvailableAddons();
$shouldRooming = $tour->templates->count();
@endphp

@push('header-stack')
    <style>
        .hidden {
            display: none !important;
        }
    </style>
    <script type="text/javascript">
        function accept() {
            $('.shown').hide();
            $('.hidden').removeClass('hidden');
        }
    </script>
@endpush

@section('footer-script')
    <script>
        function applyActivityUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.booking.upgrade-activity', ['token' => $booking->token,]) }}',
                    {   '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
    </script>
@endsection

@section('booking-body')
    @include('partials.customer.booking.summary.travellers', ['booking' => $booking, 'shouldRooming' => $shouldRooming])

    @if($lead->accommodation()->count() > 0)
        @include('partials.customer.booking.summary.accommodation', ['traveller' => $lead,])
    @endif

    @if($lead->activities()->count() > 0)
        @include('partials.customer.booking.summary.activities', ['traveller' => $lead,])
    @endif

    @if($lead->flights()->count() > 0)
        @include('partials.customer.booking.summary.flights', ['traveller' => $lead,])
    @endif

    @if($lead->transport()->count() > 0)
        @include('partials.customer.booking.summary.transport', ['traveller' => $lead,])
    @endif

    @if(sizeof($leadAddons) > 0)
        @include('partials.customer.booking.summary.addons', ['traveller' => $lead, 'addons' => $leadAddons])
    @endif

    @include('partials.customer.booking.summary.schedule', ['booking' => $booking,])

    <div class="shown">
        <x-customer.accordion id="cost-collapse" nobg>
            <x-slot:header>
                <h2 class="col-md-12 mb-0">Terms and Conditions</h2>
            </x-slot:header>
            {!! $tour->terms !!}
            <br />
            <a href="javascript:accept()" class="btn btn-success">Accept the Terms and Conditions</a>
        </x-customer.accordion>
    </div>

    @include('partials.customer.booking.summary.cost', ['booking' => $booking,])

    <div class="card hidden">
        <div class="card-body">
            <h2 class="col-md-12 mb-0">Make Payment</h2>
        </div>
    </div>
    <div class="card hidden">
        <div class="card-body">
            <form class="form-material" action="{{ route('customer-booking.deposit', ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="booking_reference" id="form-booking-reference">
                <div class="form-material row">
                    <x-customer.input name="amount" value="{{ $booking->tour->deposit * $booking->traveller_count }}" width="10" required>
                        How much do you want to pay today?
                    </x-customer.input>
                    <div class="form-group col-12 col-xl-2" style="padding-top: 19px;">
                        <input class="btn btn-primary text-white" type="submit" value="Make Payment">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
