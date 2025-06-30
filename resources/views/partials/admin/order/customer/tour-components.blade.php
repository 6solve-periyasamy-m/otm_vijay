@php 
/**
 * @param \App\Models\Order\Order $order;
 */ 
@endphp
@if($orderCustomer->is_travelling || $orderCustomer->repository->hasComponents())
    <div class="d-flex justify-content-between align-items-center pt-2 pb-md-3 pb-2">
        <h3 class="fw-bold mb-0">Tour Components</h3>
        <a href="{{$customerViewUrl}}" class="btn btn-outline-primary d-flex align-items-center gap-1">
            <i class="fas fa-eye"></i>   <!-- Bootstrap Icons -->
            <span class="d-none d-sm-inline">View Order customer</span>
        </a>
    </div>
    {{-- Components Section --}}
    <x-admin.section.card>
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                    {{ Icon::accommodation() }} Accommodation
                </button>
            </li>
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    {{ Icon::activity() }} Activities
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    {{ Icon::flight() }}
                    Flights
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                    {{ Icon::transport() }}
                    Transport
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#merchandise">
                    {{ Icon::merchandise() }}
                    Merchandise
                </button>
            </li>
        </ul>

        {{-- Tables Definition --}}
        <div id="tables" class="tab-content otm-tab-content">
            {{-- Accommodation Table --}}
            <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                <div id="accommodation-details">
                    <table id="accommodation-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Room Type</th>
                            <th scope="col">Board Type</th>
                            <th scope="col">Shared With</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Purchase Price</th>
                            <th scope="col">Upgrades</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderAccommodation as $orderAccommodation)
                            <tr component="{{ $orderAccommodation->id }}">
                                <td style="min-width: 200px">{{ f_datetime($orderAccommodation->tourComponent->inventory->check_in) }} to {{ f_datetime($orderAccommodation->tourComponent->inventory->check_out) }}</td>
                                <td>{{ $orderAccommodation->tourComponent->inventory->accommodation->name }}</td>
                                <td>{{ $orderAccommodation->tourComponent->inventory->roomType->name }}</td>
                                <td>{{ $orderAccommodation->tourComponent->inventory->boardType->name }}</td>
                                <td>{{ empty($orderAccommodation->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderAccommodation->group->getMembers($orderCustomer) }}</td>
                                <td>{{ $orderAccommodation->tourComponent->tour_component_type }}</td>
                                <td>
                                    @if($orderAccommodation->tourComponent->tour_component_type == 'Included')
                                        {{ f_currency(0) }}
                                    @else
                                        {{ f_currency($orderAccommodation->cost) }}
                                    @endif
                                </td>
                                <td>{{ f_currency($orderAccommodation->purchase_price) }} @includeWhen($orderAccommodation->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])</td>
                                <td style="width: 20%">
                                    @if($orderAccommodation->tourComponent->tour_component_type == 'Add-on')
                                        Not Available
                                    @else
                                        @if(count($orderAccommodation->tourComponent->repository->getUpgradeKeyMap()) < 2)
                                            No Upgrades Available
                                        @else
                                            @include('partials.fields.selector.adder-preset',
                                                ['field' => 'accommodation_' . $orderAccommodation->id . '_upgrade', 'preselect' => false,
                                                'createRoute' => '#', 'onclick' => 'applyAccommodationUpgrade("accommodation_' . $orderAccommodation->id . '_upgrade-input", this)', 'target' => '',
                                                'selected' => $orderAccommodation->tourComponent->repository->getUpgradeId(), 'options' => $orderAccommodation->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                        @endif
                                    @endif
                                </td>                                
                            </tr>
                        @endforeach                    
                    </table>
                </div>
            </div>
            {{-- Activities Table --}}
            <div id="activities" role="tabpanel" class="tab-pane fade">
                <div id="activities-details">
                    <table id="activities-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Ticket Type</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Purchase Price</th>
                            <th scope="col">Upgrades</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderActivities as $orderActivity)
                            <tr component="{{ $orderActivity->id }}">
                                <td style="min-width: 200px">{{ f_datetime($orderActivity->activity_inventory->starts_at) }} to {{ f_datetime($orderActivity->activity_inventory->ends_at) }}</td>
                                <td>{{ $orderActivity->activity->name }}</td>
                                <td>{{ $orderActivity->activity->activityType->name }}</td>
                                <td>{{ $orderActivity->activity_inventory->ticketType->name }}</td>
                                <td>{{ $orderActivity->tourComponent->tour_component_type }}</td>
                                <td>
                                    @if($orderActivity->tourComponent->tour_component_type == 'Included')
                                        {{ f_currency(0) }}
                                    @else
                                        {{ f_currency($orderActivity->cost) }}
                                    @endif
                                </td>
                                <td>{{ f_currency($orderActivity->purchase_price) }} @includeWhen($orderActivity->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])</td>
                                <td style="width: 20%">
                                    @if($orderActivity->tourComponent->tour_component_type == 'Add-on')
                                        Not Available
                                    @else
                                        @if(count($orderActivity->tourComponent->repository->getUpgradeKeyMap(0, true)) < 2)
                                            No Upgrades Available
                                        @else
                                            @include('partials.fields.selector.adder-preset',
                                                ['field' => 'activity_' . $orderActivity->id . '_upgrade', 'preselect' => false,
                                                'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $orderActivity->id . '_upgrade-input", this)', 'target' => '',
                                                'selected' => $orderActivity->tourComponent->repository->getUpgradeId(), 'options' => $orderActivity->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                        @endif
                                    @endif
                                </td>                                
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            {{-- Flights Table --}}
            <div id="flights" role="tabpanel" class="tab-pane fade">
                <div id="flights-details">
                    <table id="flights-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Flight Details</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Purchase Price</th>
                            <th scope="col">Upgrades</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderFlights as $orderFlight)
                            <tr component="{{ $orderFlight->id }}">
                                <td style="min-width: 200px">{{ f_datetime($orderFlight->flight_inventory->departs_at) }} to {{ f_datetime($orderFlight->flight_inventory->arrives_at) }}</td>
                                <td>{{ $orderFlight->flight_number }}</td>
                                <td>{{ $orderFlight->flight->departureAirport->name }} to {{ $orderFlight->flight->arrivalAirport->name }}</td>
                                <td>{{ $orderFlight->flight_inventory->travelClass->name }}</td>
                                <td>{{ $orderFlight->flightInventoryTour->tour_component_type }}</td>
                                <td>
                                    @if($orderFlight->tourComponent->tour_component_type == 'Included')
                                        {{ f_currency(0) }}
                                    @else
                                        {{ f_currency($orderFlight->cost) }}
                                    @endif
                                </td>
                                <td>{{ f_currency($orderFlight->purchase_price) }} @includeWhen($orderFlight->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])</td>
                                <td style="width: 20%">
                                    @if($orderFlight->tourComponent->tour_component_type == 'Add-on')
                                        Not Available
                                    @else
                                        @if(count($orderFlight->tourComponent->repository->getUpgradeKeyMap(0, true)) < 2)
                                            No Upgrades Available
                                        @else
                                            @include('partials.fields.selector.adder-preset',
                                                ['field' => 'flight_' . $orderFlight->id . '_upgrade', 'preselect' => false,
                                                'createRoute' => '#', 'onclick' => 'applyFlightUpgrade("flight_' . $orderFlight->id . '_upgrade-input", this)', 'target' => '',
                                                'selected' => $orderFlight->tourComponent->repository->getUpgradeId(), 'options' => $orderFlight->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                        @endif
                                    @endif
                                </td>                                
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            {{-- Transports Table --}}
            <div id="transports" role="tabpanel" class="tab-pane fade">
                <div id="transports-details">
                    <table id="transports-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Transport Type</th>
                            <th scope="col">Transport Information</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Purchase Price</th>
                            <th scope="col">Upgrades</th>
                        </tr>
                        </thead>
                            @foreach($orderCustomer->orderTransports as $orderTransport)
                                @php $inventory = $orderTransport->transport_inventory; @endphp
                                @if($inventory && $inventory->hasSufficientOccupancy($payingCount))
                                <tr component="{{ $orderTransport->id }}">
                                    <td style="min-width: 200px">{{ f_datetime($orderTransport->repository->getStartTime()) }} to {{ f_datetime($orderTransport->repository->getEndTime()) }}</td>
                                    <td>{{ $orderTransport->transport->name }}</td>
                                    <td>{{ $orderTransport->transport->transportType->name }}</td>
                                    <td>{{ $orderTransport->transport->departureAddress->name }} to {{ $orderTransport->transport->arrivalAddress->name }}</td>
                                    <td>{{ $orderTransport->transport_inventory->travelClass->name }}</td>
                                    <td>{{ $orderTransport->transportInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderTransport->tourComponent->tour_component_type == 'Included')
                                            {{ f_currency(0) }}
                                        @else
                                            {{ f_currency($orderTransport->cost) }}
                                        @endif
                                    </td>
                                    <td>{{ f_currency($orderTransport->purchase_price) }} @includeWhen($orderTransport->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', [])</td>
                                    <td style="width: 20%">
                                        @if($orderTransport->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderTransport->tourComponent->repository->getUpgradeKeyMap(0, true)) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'transport_' . $orderTransport->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyTransportUpgrade("transport_' . $orderTransport->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => $orderTransport->tourComponent->repository->getUpgradeId(), 'options' => $orderTransport->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
            </div>
            {{-- Merchandise Table --}}
            <div id="merchandise" role="tabpanel" class="tab-pane fade">
                <!-- Merchandise Table -->
                 <div id="merchandise-details">
                    <table id="merchandise-table" class="datatable table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Fulfilled</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderMerchandise()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.component')->get() as $orderMerchandise)
                            <tr>
                                <td><img src="{{ $orderMerchandise->tourComponent->inventory->asset }}" class="image tiny"/>
                                </td>
                                <td>{{ $orderMerchandise->tourComponent->inventory->component->name }}
                                    ({{ $orderMerchandise->tourComponent->inventory->variant->name }})
                                    ({{ $orderMerchandise->tourComponent->inventory->size?->name ?? 'No Size'  }})
                                </td>
                                <td>{{ f_currency($orderMerchandise->tourComponent->tour_sales_price) }}</td>
                                <td>{{ $orderMerchandise->tourComponent->tour_component_type }}</td>
                                <td>{{ f_bool($orderMerchandise->fulfilled) }}</td>                            
                            </tr>
                        @endforeach
                    </table>
                </div>
                 <!-- Endof -->
            </div>
        </div>
    </x-admin.section.card>   
@else
    <div class="alert alert-warning">No tour components available for this customer.</div>
@endif



