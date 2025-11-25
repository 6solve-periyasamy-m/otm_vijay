@extends('layout.customer-standard')

@php
/**
 * @var \App\Models\Order\OrderCustomer $orderCustomer
 */
@endphp

@section('title', 'Your Extras')

@php
    /**
     * @var \App\Models\Order\OrderCustomer $orderCustomer
     * @var \App\Models\Order\OrderCustomer[] $editable
     */
    $locked = $orderCustomer->order->tour->repository->isComponentsLocked();
    $addons = $orderCustomer->repository->getAvailableToAdd();
@endphp

@push('footer-stack')
    <script type="text/javascript">
        @if(!flag('payment.required', true))
            function applyUpgrade(selector, btn, model) {
                let upgrade_id = $('#' + selector).find(':selected').val();
                let component_id = $(btn).closest('tr').attr('component');
                if (upgrade_id != null && component_id != null) {
                    $.post('{{ route('api.order.customer.upgrade') }}',
                        { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                            '_token': '{{ csrf_token() }}',
                            'component': component_id,
                            'upgrade': upgrade_id,
                            'model': model
                        })
                        .done(function (xhr, textStatus, errorThrown) {
                            if (xhr.success) location.reload();
                            else alert(xhr.message);
                        })
                        .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
                }
            }
        @endif
        function purchaseUpgrade(selector, btn, model) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                loader(true);
                $.post('{{ route('api.order.customer.upgrade.purchase') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component': component_id,
                        'upgrade': upgrade_id,
                        'model': model,
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) window.location = xhr.location;
                        else {
                            alert(xhr.message);
                            loader(false);
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); loader(false); });
            }
        }
        function loader(toggle = true) {
            if (toggle) {
                $('.waiter').show();
            } else {
                $('.waiter').hide();
            }
        }
        loader(false);
    </script>
@endpush

@section('content')
<div class="row payment-balance">
    <div class="col-12">
        <form class="form-horizontal mx-2">
            <div class="form-group order-select-wrapper">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                    @foreach($orders as $order)
                    <option value='{{ $order->booking_reference }}' @if($orderCustomer->order_id == $order->id) selected @endif @if($order->cancelled) disabled @endif>{{ $order->tour->name }} ({{ $order->booking_reference }}@if($order->cancelled) (Cancelled)@endif&#41;</option>
                    @endforeach
                </select>
                <a href="{{ route('customer.invoice', ['reference' => $order->booking_reference]) }}" target="_blank" class="nvoice btn btn-primary">Invoice</a>
                @if ($orderCustomer->order->has_atol)
                    <a href="{{ route('customer.atol', ['reference' => $order->booking_reference]) }}" target="_blank" class="atol btn btn-secondary">ATOL Certificate</a>
                @endif
            </div>
        </form>
    </div>
    @php
        $order = $orderCustomer->order;
    @endphp
        <div class="container">
            <div class="row">
                @if(sizeof($editable ?? []) > 1)
                    <div class="col-sm-12 col-md-3">
                        <div class="card other-profile col-md-12 col-xs-2" onclick="window.location = '{{ route('customer.extras', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer,]) }}'">
                            <div class="card-body profile-card">
                                <center class="mt-4">
                                    <h4 class="card-title mt-2 additional-customer-title">{{ $orderCustomer->customer->first_name }} {{ $orderCustomer->customer->last_name }}</h4>
                                    <h6 class="card-subtitle additional-customer-subtitle">{{ $orderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                                </center>
                            </div>
                        </div>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Customers
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @foreach($editable as $editableOrderCustomer)
                                                @if ($editableOrderCustomer->id === $orderCustomer->id) @continue @endif
                                                <div class="card other-profile col-md-12 col-xs-2" onclick="window.location = '{{ route('customer.extras', ['reference' => $order->booking_reference, 'customer' => $editableOrderCustomer->customer,]) }}'">
                                                    <div class="card-body profile-card">
                                                        <center class="mt-4">
                                                            <h4 class="card-title mt-2 additional-customer-title">{{ $editableOrderCustomer->customer->first_name }} {{ $editableOrderCustomer->customer->last_name }}</h4>
                                                            <h6 class="card-subtitle additional-customer-subtitle">{{ $editableOrderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                                                        </center>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-sm-12 {{ sizeof($editable ?? []) > 1 ? 'col-md-9' : 'col-md-12' }}">

                <div class="card">
                    <div class="card-body">
                        <span class="h2">Order {{ $orderCustomer->order->booking_reference }} - {{ $orderCustomer->order->tour->name }}</span><br />
                        @if(!$locked)
                        Please select Customer for whom you wish to purchase the Upgrade or Add-On for from the left hand list
                        @else
                        Components are locked for this tour. If you wish to view the components of another customer, please select them from the left-hand side
                        @endif
                    </div>
                </div>
                {{-- Accommodation --}}
                @if($orderCustomer->orderAccommodation()->count() > 0)
                <div class="accordion" style="box-shadow: none;">
                    <div class="card card-heading accordion-header">
                        <div class="card-body accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseAccommodation" aria-expanded="true" aria-controls="collapseAccommodation">
                            <div>
                                <span class="h2">Accommodation</span><br />
                                <span class="hide-on-mobile">@include('partials.customer.instructions')</span>
                            </div>
                        </div>
                    </div>
                    <div class="card accordion-collapse show" id="collapseAccommodation">
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
                                                <th scope="col">Available Upgrades</th>
                                            </tr>
                                        </thead>
                                        @foreach($accommodation as $orderComponent)
                                            <tr component="{{ $orderComponent->id }}">
                                                <td style="min-width: 200px" data-content="Date">{{ f_datetime($orderComponent->accommodation_inventory->check_in) }} to {{ f_datetime($orderComponent->accommodation_inventory->check_out) }}</td>
                                                <td data-content="Name">{{ $orderComponent->accommodation->name }}</td>
                                                <td data-content="Room Info">{{ $orderComponent->accommodation_inventory->roomType->name }}, {{ $orderComponent->accommodation_inventory->boardType->name }}</td>
                                                <td data-content="Shared With">{{ empty($orderComponent->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderComponent->group->getMembers($orderCustomer) }}</td>
                                                @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                                    <td colspan="2" data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                @else
                                                    <td data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                    <td data-content="Cost">
                                                        {{ f_currency($orderComponent->cost) }}
                                                    </td>
                                                @endif
                                                <td data-content="Available Upgrades">
                                                    @if($orderComponent->tourComponent->tour_component_type == 'Add-on' || $locked)
                                                        Not Available
                                                    @else
                                                        @if(count($orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false)) < 1)
                                                            No Upgrades Available
                                                        @else
                                                            @include('partials.fields.selector.upgrade-purchase',
                                                                ['field' => 'accommodation_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                                'createRoute' => '#', 'purchaseRoute' => '#',
                                                                'onclick' => 'event.preventDefault();applyUpgrade("accommodation_' . $orderComponent->id . '_upgrade-input", this, \'accommodation\')',
                                                                'onclickPurchase' => 'event.preventDefault();purchaseUpgrade("accommodation_' . $orderComponent->id . '_upgrade-input", this, \'accommodation\')',
                                                                'target' => '',
                                                                'selected' => $orderComponent->tourComponent->repository->getUpgradeId(),
                                                                'options' => $orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false),])
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
                </div>
                @endif
                {{-- Activities --}}
                @if($orderCustomer->orderActivities()->count() > 0)
                {{-- Activities --}}
                <div class="accordion" style="box-shadow: none;">
                    <div class="card card-heading accordion-header">
                        <div class="card-body accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseActivities" aria-expanded="true" aria-controls="collapseActivities">
                            <div>
                                <span class="h2">Activities</span><br />
                                <span class="hide-on-mobile">@include('partials.customer.instructions')</span>
                            </div>
                        </div>
                    </div>
                    <div class="card accordion-collapse show" id="collapseActivities">
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
                                            <th scope="col">Available Upgrades</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($activities as $orderComponent)
                                            <tr component="{{ $orderComponent->id }}">
                                                <td style="min-width: 200px" data-content="Date">{{ f_datetime($orderComponent->activity_inventory->starts_at) }} to {{ f_datetime($orderComponent->activity_inventory->ends_at) }}</td>
                                                <td data-content="Name">{{ $orderComponent->activity->name }}</td>
                                                <td data-content="Ticket Type">{{ $orderComponent->activity_inventory->ticketType->name }}</td>
                                                @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                                    <td colspan="2" data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                @else
                                                    <td data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                    <td data-content="Cost">
                                                        {{ f_currency($orderComponent->cost) }}
                                                    </td>
                                                @endif
                                                <td data-content="Available Upgrades">
                                                    @if($orderComponent->tourComponent->tour_component_type == 'Add-on' || $locked)
                                                        Not Available
                                                    @else
                                                        @if(count($orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false)) < 1)
                                                            No Upgrades Available
                                                        @else
                                                            @include('partials.fields.selector.upgrade-purchase',
                                                                ['field' => 'activity_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                                'createRoute' => '', 'purchaseRoute' => '',
                                                                'onclick' => 'event.preventDefault();applyUpgrade("activity_' . $orderComponent->id . '_upgrade-input", this, \'activity\')',
                                                                'onclickPurchase' => 'event.preventDefault();purchaseUpgrade("activity_' . $orderComponent->id . '_upgrade-input", this, \'activity\')',
                                                                'target' => '',
                                                                'selected' => $orderComponent->tourComponent->repository->getUpgradeId(),
                                                                'options' => $orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false),])
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{-- Flights --}}
                @if($orderCustomer->orderFlights()->count() > 0)
                <div class="accordion" style="box-shadow: none;">
                    <div class="card card-heading accordion-header">
                        <div class="card-body accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFlight" aria-expanded="true" aria-controls="collapseAccommodation">
                            <div>
                                <span class="h2">Flights</span><br />
                                <span class="hide-on-mobile">@include('partials.customer.instructions')</span>
                            </div>
                        </div>
                    </div>
                    <div class="card accordion-collapse show" id="collapseFlight">
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
                                            <th scope="col">Available Upgrades</th>
                                        </tr>
                                        </thead>
                                        @foreach($flights as $orderComponent)
                                            <tr component="{{ $orderComponent->id }}">
                                                <td style="min-width: 200px" data-content="Date">{{ f_datetime($orderComponent->flight_inventory->departs_at) }} to {{ f_datetime($orderComponent->flight_inventory->arrives_at) }}</td>
                                                <td data-content="Flight Number">{{ $orderComponent->flight_number }}</td>
                                                <td data-content="Flight Details">{{ $orderComponent->flight->departureAirport->name }} to {{ $orderComponent->flight->arrivalAirport->name }}</td>
                                                <td data-content="Travel Class">{{ $orderComponent->flight_inventory->travelClass->name }}</td>
                                                @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                                    <td colspan="2" data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                @else
                                                    <td data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                    <td data-content="Cost">
                                                        {{ f_currency($orderComponent->cost) }}
                                                    </td>
                                                @endif
                                                <td data-content="Available Upgrades">
                                                    @if($orderComponent->tourComponent->tour_component_type == 'Add-on' || $locked)
                                                        Not Available
                                                    @else
                                                        @if(count($orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false)) < 1)
                                                            No Upgrades Available
                                                        @else
                                                            @include('partials.fields.selector.upgrade-purchase',
                                                                ['field' => 'flight_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                                'createRoute' => '#', 'purchaseRoute' => '#',
                                                                'onclick' => 'event.preventDefault();applyUpgrade("flight_' . $orderComponent->id . '_upgrade-input", this, \'flight\')',
                                                                'onclickPurchase' => 'event.preventDefault();purchaseUpgrade("flight_' . $orderComponent->id . '_upgrade-input", this, \'flight\')',
                                                                'target' => '',
                                                                'selected' => $orderComponent->tourComponent->repository->getUpgradeId(),
                                                    'options' => $orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false),])
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
                </div>
                @endif
                {{-- Transport --}}
                @if($orderCustomer->orderTransports()->count() > 0)
                <div class="accordion" style="box-shadow: none;">
                    <div class="card card-heading accordion-header">
                        <div class="card-body accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTransport" aria-expanded="true" aria-controls="collapseTransport">
                            <div>
                                <span class="h2">Transport</span><br />
                                <span class="hide-on-mobile">@include('partials.customer.instructions')</span>
                            </div>
                        </div>
                    </div>
                    <div class="card show" id="collapseTransport">
                        <div class="card-body">
                            <div id="transports">
                                <div id="transports-details">
                                    <table id="transports-table" class="table table-striped table-responsive-sm text-center">
                                        <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Transport Type</th>
                                            <th scope="col">Transport Number</th>
                                            <th scope="col">Transport Information</th>
                                            <th scope="col">Travel Class</th>
                                            <th scope="col">Component Type</th>
                                            <th scope="col">Cost</th>
                                            <th scope="col">Available Upgrades</th>
                                        </tr>
                                        </thead>
                                        @foreach($transports as $orderComponent)
                                            <tr component="{{ $orderComponent->id }}">
                                                <td style="min-width: 200px" data-content="Date">{{ f_datetime($orderComponent->transport_inventory->departs_at) }} to {{ f_datetime($orderComponent->transport_inventory->arrives_at) }}</td>
                                                <td data-content="Name">{{ $orderComponent->transport->name }}</td>
                                                <td data-content="Transport Type">{{ $orderComponent->transport->transportType->name }}</td>
                                                <td data-content="Transport Number">{{ $orderComponent->tourComponent->inventory->transport_number ?? 'Not Set'}}</td>
                                                <td data-content="Transport Information">{{ $orderComponent->transport->departureAddress->name }} to {{ $orderComponent->transport->arrivalAddress->name }}</td>
                                                <td data-content="Travel Class">{{ $orderComponent->transport_inventory->travelClass->name }}</td>
                                                @if($orderComponent->tourComponent->tour_component_type === 'Included')
                                                    <td colspan="2" data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                @else
                                                    <td data-content="Component Type">
                                                        {{ $orderComponent->tourComponent->tour_component_type }}
                                                    </td>
                                                    <td data-content="Cost">
                                                        {{ f_currency($orderComponent->cost) }}
                                                    </td>
                                                @endif
                                                <td data-content="Available Upgrades">
                                                    @if($orderComponent->tourComponent->tour_component_type == 'Add-on' || $locked)
                                                        Not Available
                                                    @else
                                                        @if(count($orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false)) < 1)
                                                            No Upgrades Available
                                                        @else
                                                            @include('partials.fields.selector.upgrade-purchase',
                                                                ['field' => 'transport_' . $orderComponent->id . '_upgrade', 'preselect' => false,
                                                                'createRoute' => '#', 'purchaseRoute' => '#',
                                                                'onclick' => 'event.preventDefault();applyUpgrade("transport_' . $orderComponent->id . '_upgrade-input", this, \'transport\')',
                                                                'onclickPurchase' => 'event.preventDefault();purchaseUpgrade("transport_' . $orderComponent->id . '_upgrade-input", this, \'transport\')',
                                                                'target' => '',
                                                                'selected' => $orderComponent->tourComponent->repository->getUpgradeId(),
                                                    'options' => $orderComponent->tourComponent->repository->getUpgradeKeyMap(1, false, false),])
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
                </div>
                @endif
                {{-- Add-ons and Extras --}}
                @if(!$locked && sizeof($addons) > 0)
                <div class="accordion" style="box-shadow: none;">
                    <div class="card card-heading accordion-header">
                        <div class="card-body accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseAddon" aria-expanded="true" aria-controls="collapseAddon">
                            <div>
                                <span class="h2">Add-ons and Extras</span><br />
                                <span class="hide-on-mobile">@include('partials.customer.instructions')</span>
                            </div>
                        </div>
                    </div>
                    <div class="card show" id="collapseAddon">
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
                                    @foreach($addons as $orderComponent)
                                        <tr>
                                            <td data-content="Name">{{ $orderComponent['name'] }}</td>
                                            @if($orderComponent['type'] === 'Included')
                                                <td colspan="2" data-content="Component Type">
                                                    {{ $orderComponent['type'] }}
                                                </td>
                                            @else
                                                <td data-content="Component Type">
                                                    {{ $orderComponent['type'] }}
                                                </td>
                                                <td data-content="Cost">
                                                    {{ f_currency($orderComponent['cost']) }}
                                                </td>
                                            @endif
                                            <td data-content="Actions">
                                                @if($orderComponent['owned'])
                                                    Owned
                                                @else
                                                <div style="display: flex; justify-content: center;" class="hide-on-mobile">
                                                    @if(!(flag('payment.required', true)))
                                                    <a href="{{ route('customer.extras.apply',
                                                        ['reference' => $order->booking_reference, 'componentType' => $orderComponent['component'],
                                                         'componentId' => $orderComponent['id'], 'customer' => $orderCustomer->customer,]) }}"
                                                       class="btn btn-success ms-1">+</a>
                                                    @endif
                                                    <a href="{{ route('customer.extras.purchase',
                                                        ['reference' => $order->booking_reference, 'componentType' => $orderComponent['component'],
                                                         'componentId' => $orderComponent['id'], 'customer' => $orderCustomer->customer,]) }}"
                                                       class="btn btn-primary ms-1">$</a>
                                                </div>
                                                <div style="display: flex; justify-content: center;" class="show-on-mobile-flex">
                                                    @if(!(flag('payment.required', true)))
                                                    <a href="{{ route('customer.extras.apply',
                                                        ['reference' => $order->booking_reference, 'componentType' => $orderComponent['component'],
                                                         'componentId' => $orderComponent['id'], 'customer' => $orderCustomer->customer,]) }}"
                                                       class="btn btn-success ms-1">Add to Cart</a>
                                                    @endif
                                                    <a href="{{ route('customer.extras.purchase',
                                                        ['reference' => $order->booking_reference, 'componentType' => $orderComponent['component'],
                                                         'componentId' => $orderComponent['id'], 'customer' => $orderCustomer->customer,]) }}"
                                                       class="btn btn-primary ms-1">Buy Now</a>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="waiter">
    <x-loading-spinner center></x-loading-spinner>
</div>

@endsection

@section('footer-script')
<script>
    function onOrderChange(selector) {
        let invoiceRoute = "{{ route('customer.invoice', ['reference' => 'reference']) }}";
        let atolRoute = "{{ route('customer.atol', ['reference' => 'reference']) }}";
        let newBooking = $('.order-select').val()
        $('.order').hide();
        $('.order-' + newBooking).show();
        $('#form-booking-reference').val(newBooking);
        $('.invoice').prop('href', invoiceRoute.replace('reference', newBooking));
        $('.atol').prop('href', atolRoute.replace('reference', newBooking));
        if ($(selector).val() !== undefined) {
            window.location = "{{ route('customer.extras') }}" + '/' + $(selector).val();
        }
    }
    onOrderChange();
</script>
@endsection
