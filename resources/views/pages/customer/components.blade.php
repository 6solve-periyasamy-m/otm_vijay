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
                    <div id="accommodation-details">
                        <table id="accommodation-table" class="table table-striped table-responsive-sm text-center">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Room Info</th>
                                <th scope="col">Shared With</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderAccommodation() as $orderComponent)
                                <tr component="{{ $orderComponent->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderComponent->accommodationInventory->check_in) }} to {{ StringFormatter::formatDateTime($orderComponent->accommodationInventory->check_out) }}</td>
                                    <td>{{ $orderComponent->accommodation->name }}</td>
                                    <td>{{ $orderComponent->accommodationInventory->roomType->name }}, {{ $orderComponent->accommodationInventory->boardType->name }}</td>
                                    <td>{{ empty($orderComponent->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderComponent->group->getMembers($orderCustomer) }}</td>
                                    @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                        <td colspan="2">
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                        <td>
                                            {{ StringFormatter::formatCurrency($orderComponent->cost) }}
                                        </td>
                                    @endif
                                    <td style="width: 20%">
                                        @if($orderComponent->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'accommodation_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyAccommodationUpgrade("accommodation_' . $orderComponent->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromAccommodation($orderComponent->tourComponent), 'options' => $orderComponent->tourComponent->getCustomerUpgradeKeyMap(),])
                                            @endif
                                        @endif
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
                    <div id="activities-details">
                        <table id="activities-table" class="table table-striped table-responsive-sm text-center">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Ticket Type</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderActivities as $orderComponent)
                                <tr component="{{ $orderComponent->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderComponent->activityInventory->starts_at) }} to {{ StringFormatter::formatDateTime($orderComponent->activityInventory->ends_at) }}</td>
                                    <td>{{ $orderComponent->activity->name }}</td>
                                    <td>{{ $orderComponent->activityInventory->ticketType->name }}</td>
                                    @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                        <td colspan="2">
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                        <td>
                                            {{ StringFormatter::formatCurrency($orderComponent->cost) }}
                                        </td>
                                    @endif
                                    <td style="width: 20%">
                                        @if($orderComponent->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'activity_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $orderComponent->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromActivity($orderComponent->tourComponent), 'options' => $orderComponent->tourComponent->getCustomerUpgradeKeyMap(),])
                                            @endif
                                        @endif
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
                    <div id="flights-details">
                        <table id="flights-table" class="table table-striped table-responsive-sm text-center">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Flight Number</th>
                                <th scope="col">Flight Details</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderFlights as $orderComponent)
                                <tr component="{{ $orderComponent->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderComponent->flightInventory->departs_at) }} to {{ StringFormatter::formatDateTime($orderComponent->flightInventory->arrives_at) }}</td>
                                    <td>{{ $orderComponent->flightInventory->flight_number }}</td>
                                    <td>{{ $orderComponent->flight->departureAirport->name }} to {{ $orderComponent->flight->arrivalAirport->name }}</td>
                                    <td>{{ $orderComponent->flightInventory->travelClass->name }}</td>
                                    @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                        <td colspan="2">
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                        <td>
                                            {{ StringFormatter::formatCurrency($orderComponent->cost) }}
                                        </td>
                                    @endif
                                    <td style="width: 20%">
                                        @if($orderComponent->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'flight_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyFlightUpgrade("flight_' . $orderComponent->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromFlight($orderComponent->tourComponent), 'options' => $orderComponent->tourComponent->getCustomerUpgradeKeyMap(),])
                                            @endif
                                        @endif
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
                    <div id="transports-details">
                        <table id="transports-table" class="table table-striped table-responsive-sm text-center">
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
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderTransports as $orderComponent)
                                <tr component="{{ $orderComponent->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderComponent->transportInventory->departs_at) }} to {{ StringFormatter::formatDateTime($orderComponent->transportInventory->arrives_at) }}</td>
                                    <td>{{ $orderComponent->transport->name }}</td>
                                    <td>{{ $orderComponent->transport->transportType->name }}</td>
                                    <td>{{ $orderComponent->transport->departureAddress->name }} to {{ $orderComponent->transport->arrivalAddress->name }}</td>
                                    <td>{{ $orderComponent->transportInventory->travelClass->name }}</td>
                                    @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                        <td colspan="2">
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $orderComponent->tourComponent->tour_component_type }}
                                        </td>
                                        <td>
                                            {{ StringFormatter::formatCurrency($orderComponent->cost) }}
                                        </td>
                                    @endif
                                    <td style="width: 20%">
                                        @if($orderComponent->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'transport_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyTransportUpgrade("transport_' . $orderComponent->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromTransport($orderComponent->tourComponent), 'options' => $orderComponent->tourComponent->getCustomerUpgradeKeyMap(),])
                                            @endif
                                        @endif
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
                <div id="merchandise-details">
                    <table id="merchandise-table" class="table table-striped table-responsive-sm text-center">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->order->getAvailableAdditionals() as $orderComponent)
                            <tr>
                                <td>{{ $orderComponent['name'] }}</td>
                                @if($orderComponent['type'] === 'Included')
                                    <td colspan="2">
                                        {{ $orderComponent['type'] }}
                                    </td>
                                @else
                                    <td>
                                        {{ $orderComponent['type'] }}
                                    </td>
                                    <td>
                                        {{ StringFormatter::formatCurrency($orderComponent['cost']) }}
                                    </td>
                                @endif
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
