@extends('pages.customer.booking.layout')

@php
/**
 * @var \App\Models\Booking\Booking $booking
 */
$lead = $booking->leadTraveller;
@endphp

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
    @include('partials.customer.booking.summary.travellers', ['booking' => $booking,])

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

    @if(false && array_key_exists('addons', $customerData['components']) && sizeof($customerData['addons']) > 0)
    <div class="card">
        <div class="card-body">
            <h2 class="col-md-12 mb-0">Add-ons and Extras</h2>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="merchandise-table" class="table table-striped table-responsive-sm text-center">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($customerData['addons'] as $bookingComponent)
                    <tr>
                        <td>{{ $bookingComponent['name'] }}</td>
                        @if($bookingComponent['type'] === 'Included')
                            <td colspan="2">
                                {{ $bookingComponent['type'] }}
                            </td>
                        @else
                            <td>
                                {{ $bookingComponent['type'] }}
                            </td>
                            <td>
                                {{ f_currency($bookingComponent['cost']) }}
                            </td>
                        @endif
                        <td>
                            @if($bookingComponent['owned'])
                                <a href="{{ route('customer-booking.remove-addon',
                                            ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token, 'type' => $bookingComponent['component'],
                                             'id' => $bookingComponent['id'],]) }}"
                                   class="btn btn-danger ms-1">-</a>
                            @else
                                <a href="{{ route('customer-booking.purchase-addon',
                                            ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token, 'type' => $bookingComponent['component'],
                                             'id' => $bookingComponent['id'],]) }}"
                                   class="btn btn-success ms-1">+</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    @include('partials.customer.booking.summary.schedule', ['booking' => $booking,])

    @include('partials.customer.booking.summary.cost', ['booking' => $booking,])

    <div class="card">
        <div class="card-body">
            <h2 class="col-md-12 mb-0">Make Payment</h2>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form class="form-material" action="{{ route('customer-booking.deposit', ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="booking_reference" id="form-booking-reference">
                <div class="form-material row">
                    <div class="form-group col-12 col-xl-3">
                        <input class="form-control form-control-line" type="button" value="How much do you want to pay today?" disabled>
                    </div>
                    <div class="form-group col-12 col-xl-7">
                        <input class="form-control form-control-line" name="amount" type="text" placeholder="Amount to Pay" value="{{ number_format($booking->tour->deposit * $booking->traveller_count) }}" required/>
                    </div>
                    <div class="form-group col-12 col-xl-2">
                        <input class="form-control form-control-line" type="submit" value="Make Payment">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
