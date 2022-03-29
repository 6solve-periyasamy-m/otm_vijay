@extends('layout.customer')

@section('title', 'Your Extras')


@push('header-stack')
    <script type="text/javascript">
        const route = "{{ route('customer.extras') }}"
        function onOrderChange(selector) {
            window.location = route + '/' + $(selector).val();
        }
        @if(\App\Repository\SettingsRepository::getOrDefault('payment.required', true))
            function applyAccommodationUpgrade(selector, btn) {
                let upgrade_id = $('#' + selector).find(':selected').val();
                let component_id = $(btn).closest('tr').attr('component');
                if (upgrade_id != null && component_id != null) {
                    $.post('{{ route('api.order.customer.accommodation.upgrade') }}',
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
                    $.post('{{ route('api.order.customer.activity.upgrade') }}',
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
            function applyFlightUpgrade(selector, btn) {
                let upgrade_id = $('#' + selector).find(':selected').val();
                let component_id = $(btn).closest('tr').attr('component');
                if (upgrade_id != null && component_id != null) {
                    $.post('{{ route('api.order.customer.flight.upgrade') }}',
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
                    $.post('{{ route('api.order.customer.transport.upgrade') }}',
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
        @endif
        function purchaseAccommodationUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.customer.accommodation.upgrade.purchase') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) window.location = xhr.location;
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function purchaseActivityUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.customer.activity.upgrade.purchase') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) window.location = xhr.location;
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function purchaseFlightUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.customer.flight.upgrade.purchase') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) window.location = xhr.location;
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function purchaseTransportUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.customer.transport.upgrade.purchase') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) window.location = xhr.location;
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
                <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                    @foreach($orders as $order)
                    <option value='{{ $order->booking_reference }}' @if($order->id == $order->id) selected @endif>{{ $order->booking_reference }} - {{ $order->tour->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('customer.invoice', ['reference' => $order->booking_reference]) }}" target="_blank" class="m-l-20 invoice btn btn-primary">Invoice</a>
                @if ($orderCustomer->order->has_atol_certificate)
                    <a href="{{ route('customer.atol', ['reference' => $order->booking_reference]) }}" target="_blank" class="m-l-5 invoice btn btn-secondary">ATOL Certificate</a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <span class="h2">Order {{ $orderCustomer->order->booking_reference }} - {{ $orderCustomer->order->tour->name }}</span><br />
            </div>
        </div>
        {{-- Accommodation --}}
        <div class="card">
            <div class="card-body">
                <span class="h2">Accommodation</span><br />
                @include('partials.customer.instructions')
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
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 1)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.upgrade-purchase',
                                                    ['field' => 'accommodation_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'purchaseRoute' => '#',
                                                    'onclick' => 'event.preventDefault();applyAccommodationUpgrade("accommodation_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'onclickPurchase' => 'event.preventDefault();purchaseAccommodationUpgrade("accommodation_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'target' => '',
                                                    'selected' => \App\Repository\TourRepository::getUpgradeIdFromAccommodation($orderComponent->tourComponent),
                                                    'options' => $orderComponent->tourComponent->getCustomerUpgradeKeyMap(),])
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
                <span class="h2">Activities</span><br />
                @include('partials.customer.instructions')
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
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 1)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.upgrade-purchase',
                                                    ['field' => 'activity_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '', 'purchaseRoute' => '',
                                                    'onclick' => 'event.preventDefault();applyActivityUpgrade("activity_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'onclickPurchase' => 'event.preventDefault();purchaseActivityUpgrade("activity_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'target' => '',
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
                <span class="h2">Flights</span><br />
                @include('partials.customer.instructions')
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
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 1)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.upgrade-purchase',
                                                    ['field' => 'flight_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'purchaseRoute' => '#',
                                                    'onclick' => 'event.preventDefault();applyFlightUpgrade("flight_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'onclickPurchase' => 'event.preventDefault();purchaseFlightUpgrade("flight_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'target' => '',
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
                <span class="h2">Transport</span><br />
                @include('partials.customer.instructions')
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
                                            @if(count($orderComponent->tourComponent->getCustomerUpgradeKeyMap()) < 1)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.upgrade-purchase',
                                                    ['field' => 'transport_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'purchaseRoute' => '#',
                                                    'onclick' => 'event.preventDefault();applyTransportUpgrade("transport_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'onclickPurchase' => 'event.preventDefault();purchaseTransportUpgrade("transport_' . $orderComponent->id . '_upgrade-input", this)',
                                                    'target' => '',
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
                <span class="h2">Add-ons and Extras</span><br />
                @include('partials.customer.instructions')
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
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach(\App\Repository\OrderRepository::getOrderCustomerAdditionals($orderCustomer) as $orderComponent)
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
                                <td>
                                    @if($orderComponent['owned'])
                                        Owned
                                    @else
                                        @if(!(\App\Repository\SettingsRepository::getOrDefault('payment.required', true)))
                                        <a href="{{ route('customer.extras.apply',
                                            ['reference' => $order->booking_reference, 'componentType' => $orderComponent['component'],
                                             'componentId' => $orderComponent['id'], 'customer' => $orderCustomer->customer,]) }}"
                                           class="btn btn-success d-inline ms-1">+</a>
                                        @endif
                                        <a href="{{ route('customer.extras.purchase',
                                            ['reference' => $order->booking_reference, 'componentType' => $orderComponent['component'],
                                             'componentId' => $orderComponent['id'], 'customer' => $orderCustomer->customer,]) }}"
                                           class="btn btn-primary d-inline ms-1">$</a>
                                    @endif
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
