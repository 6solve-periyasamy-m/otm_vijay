@extends('layout.customer')

@section('title', 'Balance & Payment')


@push('header-stack')
    <script type="text/javascript">
        function addAccommodationAddon() {
            let id = $('#accommodation_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.accommodation') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'accommodation_id': id})
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }

        function addActivityAddon() {
            let id = $('#activity_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.activity') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'activity_id': id})
                    .done(function () { location.reload();})
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function addFlightAddon() {
            let id = $('#flight_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.flight') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'flight_id': id})
                    .done(function () { location.reload();})
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function addTransportAddon() {
            let id = $('#transport_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.transport') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'transport_id': id})
                    .done(function () { location.reload();})
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function addMerchandiseAddon() {
            let id = $('#merchandise_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.merchandise') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'merchandise_id': id})
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function applyAccommodationUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.accommodation.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
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
        function applyActivityUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.activity.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
            console.log(upgrade_id);
            console.log(component_id);
        }
        function applyFlightUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.flight.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
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
        function applyTransportUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.transport.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
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
@endpush

@section('content')
<div class="row payment-balance">
    <div class="col-12">
        <form class="form-horizontal mx-2">
            <div class="form-group d-flex align-items-center">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange();" id="booking_reference">
                    @foreach($orders as $order)
                    <option value='{{ $order->booking_reference }}'>{{ $order->booking_reference }}</option>
                    @endforeach
                </select>
                <a href="#" class="invoice btn btn-primary">Invoice</a>
            </div>
        </form>
    </div>
    <div class="col-12">
        {{-- Accommodation --}}
        <div class="card">
            <div class="card-body">
                <span class="h2">Accommodation</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="accommodation">
                    <div id="accommodation-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        @include('partials.fields.selector.adder',
                            ['field' => 'accommodation_id', 'preselect' => false,
                            'fullRoute' => route('api.available-accommodation.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addAccommodationAddon()', 'target' => ''])
                    </div>
                    <div id="accommodation-details">
                        <table id="accommodation-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Room Type</th>
                                <th scope="col">Board Type</th>
                                <th scope="col">Shared With</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderAccommodation() as $orderAccommodation)
                                <tr component="{{ $orderAccommodation->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderAccommodation->accommodationInventory->check_in) }} to {{ StringFormatter::formatDateTime($orderAccommodation->accommodationInventory->check_out) }}</td>
                                    <td>{{ $orderAccommodation->accommodation->name }}</td>
                                    <td>{{ $orderAccommodation->accommodationInventory->roomType->name }}</td>
                                    <td>{{ $orderAccommodation->accommodationInventory->boardType->name }}</td>
                                    <td>{{ empty($orderAccommodation->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderAccommodation->group->getMembers($orderCustomer) }}</td>
                                    <td>{{ $orderAccommodation->accommodationInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderAccommodation->tourComponent->tour_component_type == 'Included')
                                            {{ StringFormatter::formatCurrency(0) }}
                                        @else
                                            {{ StringFormatter::formatCurrency($orderAccommodation->cost) }}
                                        @endif
                                    </td>
                                    <td style="width: 20%">
                                        @if($orderAccommodation->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderAccommodation->tourComponent->getUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'accommodation_' . $orderAccommodation->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyAccommodationUpgrade("accommodation_' . $orderAccommodation->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromAccommodation($orderAccommodation->tourComponent), 'options' => $orderAccommodation->tourComponent->getUpgradeKeyMap(),])
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('orderAccommodationDelete', ['id' => $orderAccommodation->id,]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['reference' => $orderCustomer->order->booking_reference,]) }}" />
                                            <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Activities --}}
        <div class="card">
            <div class="card-body">
                <span class="h2">Activities</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="activities">
                        <div id="activities-new" class="d-flex justify-content-between mb-3 flex-wrap">
                            @include('partials.fields.selector.adder',
                                ['field' => 'activity_id', 'preselect' => false,
                                'fullRoute' => route('api.available-activities.select', ['orderCustomer' => $orderCustomer,]),
                                'createRoute' => '#', 'onclick' => 'addActivityAddon()', 'target' => ''])
                        </div>
                        <div id="activities-details">
                            <table id="activities-table" class="table table-striped table-responsive-sm">
                                <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Activity Type</th>
                                    <th scope="col">Ticket Type</th>
                                    <th scope="col">Component Type</th>
                                    <th scope="col">Cost</th>
                                    <th scope="col">Upgrades</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                @foreach($orderCustomer->orderActivities as $orderActivity)
                                    <tr component="{{ $orderActivity->id }}">
                                        <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderActivity->activityInventory->starts_at) }} to {{ StringFormatter::formatDateTime($orderActivity->activityInventory->ends_at) }}</td>
                                        <td>{{ $orderActivity->activity->name }}</td>
                                        <td>{{ $orderActivity->activity->activityType->name }}</td>
                                        <td>{{ $orderActivity->activityInventory->ticketType->name }}</td>
                                        <td>{{ $orderActivity->tourComponent->tour_component_type }}</td>
                                        <td>
                                            @if($orderActivity->tourComponent->tour_component_type == 'Included')
                                                {{ StringFormatter::formatCurrency(0) }}
                                            @else
                                                {{ StringFormatter::formatCurrency($orderActivity->cost) }}
                                            @endif
                                        </td>
                                        <td style="width: 20%">
                                            @if($orderActivity->tourComponent->tour_component_type == 'Add-on')
                                                Not Available
                                            @else
                                                @if(count($orderActivity->tourComponent->getUpgradeKeyMap()) < 2)
                                                    No Upgrades Available
                                                @else
                                                    @include('partials.fields.selector.adder-preset',
                                                        ['field' => 'activity_' . $orderActivity->id . '_upgrade', 'preselect' => false,
                                                        'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $orderActivity->id . '_upgrade-input", this)', 'target' => '',
                                                        'selected' => \App\Repository\TourRepository::getUpgradeIdFromActivity($orderActivity->tourComponent), 'options' => $orderActivity->tourComponent->getUpgradeKeyMap(),])
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('orderActivityDelete', ['id' => $orderActivity->id,]) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['reference' => $orderCustomer->order->booking_reference,]) }}" />
                                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
            </div>
        </div>
        {{-- Flights --}}
        <div class="card">
            <div class="card-body">
                <span class="h2">Flights</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="flights">
                    <div id="flights-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        @include('partials.fields.selector.adder',
                            ['field' => 'flight_id', 'preselect' => false,
                            'fullRoute' => route('api.available-flights.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addFlightAddon()', 'target' => ''])
                    </div>
                    <div id="flights-details">
                        <table id="flights-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Flight Details</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderFlights as $orderFlight)
                                <tr component="{{ $orderFlight->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderFlight->flightInventory->departs_at) }} to {{ StringFormatter::formatDateTime($orderFlight->flightInventory->arrives_at) }}</td>
                                    <td>{{ $orderFlight->flightInventory->flight_number }}</td>
                                    <td>{{ $orderFlight->flight->departureAirport->name }} to {{ $orderFlight->flight->arrivalAirport->name }}</td>
                                    <td>{{ $orderFlight->flightInventory->travelClass->name }}</td>
                                    <td>{{ $orderFlight->flightInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderFlight->tourComponent->tour_component_type == 'Included')
                                            {{ StringFormatter::formatCurrency(0) }}
                                        @else
                                            {{ StringFormatter::formatCurrency($orderFlight->cost) }}
                                        @endif
                                    </td>
                                    <td style="width: 20%">
                                        @if($orderFlight->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderFlight->tourComponent->getUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'flight_' . $orderFlight->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyFlightUpgrade("flight_' . $orderFlight->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromFlight($orderFlight->tourComponent), 'options' => $orderFlight->tourComponent->getUpgradeKeyMap(),])
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('orderFlightDelete', ['id' => $orderFlight->id,]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['reference' => $orderCustomer->order->booking_reference,]) }}" />
                                            <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Transport --}}
        <div class="card">
            <div class="card-body">
                <span class="h2">Transport</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="transports">
                    <div id="transports-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        @include('partials.fields.selector.adder',
                            ['field' => 'transport_id', 'preselect' => false,
                            'fullRoute' => route('api.available-transports.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addTransportAddon()', 'target' => ''])
                    </div>
                    <div id="transports-details">
                        <table id="transports-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Transport Type</th>
                                <th scope="col">Transport Information</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderTransports as $orderTransport)
                                <tr component="{{ $orderTransport->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderTransport->transportInventory->departs_at) }} to {{ StringFormatter::formatDateTime($orderTransport->transportInventory->arrives_at) }}</td>
                                    <td>{{ $orderTransport->transport->name }}</td>
                                    <td>{{ $orderTransport->transport->transportType->name }}</td>
                                    <td>{{ $orderTransport->transport->departureAddress->name }} to {{ $orderTransport->transport->arrivalAddress->name }}</td>
                                    <td>{{ $orderTransport->transportInventory->travelClass->name }}</td>
                                    <td>{{ $orderTransport->transportInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderTransport->tourComponent->tour_component_type == 'Included')
                                            {{ StringFormatter::formatCurrency(0) }}
                                        @else
                                            {{ StringFormatter::formatCurrency($orderTransport->cost) }}
                                        @endif
                                    </td>
                                    <td style="width: 20%">
                                        @if($orderTransport->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderTransport->tourComponent->getUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'transport_' . $orderTransport->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyTransportUpgrade("transport_' . $orderTransport->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromTransport($orderTransport->tourComponent), 'options' => $orderTransport->tourComponent->getUpgradeKeyMap(),])
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('orderTransportDelete', ['id' => $orderTransport->id,]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['reference' => $orderCustomer->order->booking_reference,]) }}" />
                                            <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Add-ons and Extras --}}
        <div class="card">
            <div class="card-body">
                <span class="h2">Add-ons and Extras</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="merchandise-new" class="d-flex justify-content-between mb-3 flex-wrap">
                    @include('partials.fields.selector.adder',
                                ['field' => 'merchandise_id', 'preselect' => false,
                                'fullRoute' => route('api.available-merchandise.select', ['orderCustomer' => $orderCustomer,]),
                                'createRoute' => '#', 'onclick' => 'addMerchandiseAddon()', 'target' => ''])
                </div>
                <div id="merchandise-details">
                    <table id="merchandise-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderMerchandise as $orderMerchandise)
                            <tr>
                                <td>{{ $orderMerchandise->merchandise->name }}</td>
                                <td>{{ StringFormatter::formatCurrency($orderMerchandise->merchandise->tour_sales_price) }}</td>
                                <td>{{ $orderMerchandise->merchandise->tour_component_type }}</td>
                                <td>
                                    <form action="{{ route('orderMerchandiseDelete', ['id' => $orderMerchandise->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['reference' => $orderCustomer->order->booking_reference,]) }}" />
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
<script>
    let route = "{{ route('customer.invoice', ['reference' => 'reference']) }}"
    function onOrderChange() {
        let newBooking = $('.order-select').val()
        $('.order').hide();
        $('.order-' + newBooking).show();
        $('#form-booking-reference').val(newBooking);
        $('.invoice').prop('href', route.replace('reference', newBooking));
    }
    onOrderChange();
</script>
@endsection
