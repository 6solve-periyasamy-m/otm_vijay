@extends('layout.master')

@section('title', 'View Tour')

@section('header-script')
    <script>
        $(document).ready(function () {
            $('#accommodation-table').DataTable({fixedHeader: true});
            $('#activities-table').DataTable({fixedHeader: true});
            $('#flights-table').DataTable({fixedHeader: true});
            $('#transports-table').DataTable({fixedHeader: true});
            $('#templates-table').DataTable({fixedHeader: true});
            $('#installments-table').DataTable({fixedHeader: true});
            $('#merchandise-table').DataTable({fixedHeader: true});
            $('#orders-table').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
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
                <h6 class="fw-bold"><a target="_blank"
                                       href="{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}">{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}</a>
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
            <div class="col-12">
                @can('update', \App\Models\Tour\Tour::class)
                    <a class="btn btn-success" href="{{route('tours.edit', ['tour' => $tour,])}}">
                        <i class="icon-note"></i>
                        <span>Edit Tour</span>
                    </a>
                @endcan
                @can('update', \App\Models\Merchandise\Merchandise::class)
                    <a class="btn btn-primary" href="{{route('tours.fulfil', ['tour' => $tour,])}}">
                        <i class="icon-action-redo"></i>
                        <span>Fulfil Merchandise Orders</span>
                    </a>
                @endcan
                @can('create', \App\Models\Quote\Quote::class)
                    <a class="btn btn-primary" href="{{route('quotes.create', ['tour' => $tour,])}}">
                        <i class="icon-wallet"></i>
                        <span>Create Quote</span>
                    </a>
                @endcan
                @if($tour->has_atol_certificate)
                    <a class="btn btn-info" href="{{route('tours.atol', ['tour' => $tour,])}}">
                        <i class="icon-folder-alt"></i>
                        <span>Export ATOL Certificates</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Components</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="py-2 mb-3 text-end">
                <a href="{{ route('tours.add', ['tour' => $tour, ]) }}" class="btn btn-primary text-white">
                    <i class="icon-plus"></i>
                    <span>Add Components</span>
                </a>
            </div>
            {{-- Tabs Definition --}}
            <ul class="nav nav-pills otm-tab">
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                        <i class="icon-home"></i> Accommodation
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                        <i class="icon-settings"></i> Activities
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                        <i class="icon-plane"></i>
                        Flights
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                        <i class="icon-directions"></i>
                        Transport
                    </button>
                </li>
            </ul>
            {{-- Tables Definition --}}
            <div id="tables" class="tab-content otm-tab-content">
                {{-- Accommodation Table --}}
                <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                    <div id="accommodation-details">
                        <table id="accommodation-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Room Type</th>
                                <th scope="col">Board Type</th>
                                <th scope="col">Template?</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Bookable?</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($accommodation as $accommodationEntry)
                                <tr>
                                    <td style="min-width: 200px">
                                        {{ f_datetime($accommodationEntry["inventory"]->check_in) }}
                                        <input type="checkbox" disabled
                                               @if($accommodationEntry["inventory"]->check_in_time_confirmed == 1) checked @endif>
                                        &nbspto&nbsp
                                        {{ f_datetime($accommodationEntry["inventory"]->check_out) }}
                                        <input type="checkbox" disabled
                                               @if($accommodationEntry["inventory"]->check_out_time_confirmed == 1) checked @endif>
                                    </td>
                                    <td>{{ $accommodationEntry["component"]->name }}</td>
                                    <td>{{ $accommodationEntry["inventory"]->roomType->name }}</td>
                                    <td>{{ $accommodationEntry["inventory"]->boardType->name }}</td>
                                    <td>{{ f_bool($accommodationEntry["tour"]->is_template) }}</td>
                                    <td>
                                        @if($accommodationEntry["tour"]->tour_component_type == 'Upgrade')
                                            <abbr title="{{ $accommodationEntry["tour"]->parent() }}">
                                                @endif
                                                {{ $accommodationEntry["tour"]->tour_component_type }}
                                                @if($accommodationEntry["tour"]->tour_component_type == 'Upgrade')
                                            </abbr>
                                        @endif
                                    </td>
                                    <td>{{ f_bool($accommodationEntry["tour"]->is_bookable) }}</td>
                                    <td class="actions-3">
                                        @can('update', \App\Models\Accommodation\AccommodationInventoryTour::class)
                                            @if($accommodationEntry["tour"]->tour_component_type !== 'Add-on')
                                                <a href="{{ route('accommodation-upgrade.view', ['tour' => $tour, 'inventoryTour' => $accommodationEntry["tour"]->tour_component_type == 'Upgrade' ? $accommodationEntry["tour"]->parent() : $accommodationEntry["tour"],]) }}"
                                                   class="btn btn-outline-success btn-sm mb-1"><i
                                                            class="icon-arrow-up"></i></a>
                                            @else
                                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                                </span>
                                            @endif
                                            <a href="{{ route('accommodation-inventory-tours.edit', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationEntry["tour"],]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-arrow-up"></i>
                                            </span>
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Accommodation\AccommodationInventoryTour::class)
                                            @if($accommodationEntry["tour"]->is_bookable)
                                                <a href="#"
                                                   onclick="$('#accommodation-{{$accommodationEntry["tour"]->id}}-delete').submit()"
                                                   class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                                <form action="{{ route('accommodation-inventory-tours.delete', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationEntry["tour"],]) }}"
                                                      method="post"
                                                      id="accommodation-{{$accommodationEntry["tour"]->id}}-delete">
                                                    @csrf
                                                </form>
                                            @else
                                                <a href="#"
                                                   onclick="$('#accommodation-{{$accommodationEntry["tour"]->id}}-restore').submit()"
                                                   class="btn btn-outline-warning btn-sm mb-1"><i
                                                            class="icon-magic-wand"></i></a>
                                                <form action="{{ route('accommodation-inventory-tours.restore', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationEntry["tour"],]) }}"
                                                      method="post"
                                                      id="accommodation-{{$accommodationEntry["tour"]->id}}-restore">
                                                    @csrf
                                                </form>
                                            @endif
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                {{-- Activities Table --}}
                <div id="activities" role="tabpanel" class="tab-pane fade">
                    <div id="activities-details">
                        <table id="activities-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Activity Type</th>
                                <th scope="col">Ticket Type</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Bookable?</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($activities as $activity)
                                <tr>
                                    <td style="min-width: 200px">{{ f_datetime($activity["inventory"]->starts_at) }}
                                        to {{ f_datetime($activity["inventory"]->ends_at) }}</td>
                                    <td>{{ $activity["component"]->name }}</td>
                                    <td>{{ $activity["component"]->activityType->name }}</td>
                                    <td>{{ $activity["inventory"]->ticketType->name }}</td>
                                    <td>
                                        @if($activity["tour"]->tour_component_type == 'Upgrade')
                                            <abbr title="{{ $activity["tour"]->parent() }}">
                                                @endif
                                                {{ $activity["tour"]->tour_component_type }}
                                                @if($activity["tour"]->tour_component_type == 'Upgrade')
                                            </abbr>
                                        @endif
                                    </td>
                                    <td>{{ f_bool($activity["tour"]->is_bookable) }}</td>
                                    <td class="actions-3">
                                        @can('update', \App\Models\Activity\ActivityInventoryTour::class)
                                            @if($activity["tour"]->tour_component_type !== 'Add-on')
                                                <a href="{{ route('activity-upgrade.view', ['tour' => $tour, 'inventoryTour' => $activity["tour"]->tour_component_type == 'Upgrade' ? $activity["tour"]->parent() : $activity["tour"],]) }}"
                                                   class="btn btn-outline-success btn-sm mb-1"><i
                                                            class="icon-arrow-up"></i></a>
                                            @else
                                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                                </span>
                                            @endif
                                            <a href="{{ route('activity-inventory-tours.edit', ['tour' => $tour, 'activityInventoryTour' => $activity["tour"],]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                                </span>
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Activity\ActivityInventoryTour::class)
                                            @if($activity["tour"]->is_bookable)
                                                <a href="#"
                                                   onclick="$('#activity-{{$activity["tour"]->id}}-delete').submit()"
                                                   class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                                <form action="{{ route('activity-inventory-tours.delete', ['tour' => $tour, 'activityInventoryTour' => $activity["tour"],]) }}"
                                                      method="post" id="activity-{{$activity["tour"]->id}}-delete">
                                                    @csrf
                                                </form>
                                            @else
                                                <a href="#"
                                                   onclick="$('#activity-{{$activity["tour"]->id}}-restore').submit()"
                                                   class="btn btn-outline-warning btn-sm mb-1"><i
                                                            class="icon-magic-wand"></i></a>
                                                <form action="{{ route('activity-inventory-tours.restore', ['tour' => $tour, 'activityInventoryTour' => $activity["tour"],]) }}"
                                                      method="post" id="activity-{{$activity["tour"]->id}}-restore">
                                                    @csrf
                                                </form>
                                            @endif
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                {{-- Flights Table --}}
                <div id="flights" role="tabpanel" class="tab-pane fade">
                    <div id="flights-details">
                        <table id="flights-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Flight Type</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Bookable?</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($flights as $flight)
                                <tr>
                                    <td style="min-width: 200px">{{ f_datetime($flight["inventory"]->departs_at) }}
                                        to {{ f_datetime($flight["inventory"]->arrives_at) }}</td>
                                    <td>{{ $flight["inventory"]->flight_number }}</td>
                                    <td>{{ $flight["inventory"]->travelClass->name }}</td>
                                    <td>{{ $flight["tour"]->flight_type }}</td>
                                    <td>
                                        @if($flight["tour"]->tour_component_type == 'Upgrade')
                                            <abbr title="{{ $flight["tour"]->parent() }}">
                                                @endif
                                                {{ $flight["tour"]->tour_component_type }}
                                                @if($flight["tour"]->tour_component_type == 'Upgrade')
                                            </abbr>
                                        @endif
                                    </td>
                                    <td>{{ f_bool($flight["tour"]->is_bookable) }}</td>
                                    <td class="actions-3">
                                        @can('update', \App\Models\Flight\FlightInventoryTour::class)
                                            @if($flight["tour"]->tour_component_type !== 'Add-on')
                                                <a href="{{ route('flight-upgrade.view', ['tour' => $tour, 'inventoryTour' => $flight["tour"]->tour_component_type == 'Upgrade' ? $flight["tour"]->parent() : $flight["tour"],]) }}"
                                                   class="btn btn-outline-success btn-sm mb-1"><i
                                                            class="icon-arrow-up"></i></a>
                                            @else
                                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                                </span>
                                            @endif
                                            <a href="{{ route('flight-inventory-tours.edit', ['tour' => $tour, 'flightInventoryTour' => $flight["tour"],]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                                </span>
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Flight\FlightInventoryTour::class)
                                            @if($flight["tour"]->is_bookable)
                                                <a href="#"
                                                   onclick="$('#flight-{{$flight["tour"]->id}}-delete').submit()"
                                                   class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                                <form action="{{ route('flight-inventory-tours.delete', ['tour' => $tour, 'flightInventoryTour' => $flight["tour"],]) }}"
                                                      method="post" id="flight-{{$flight["tour"]->id}}-delete">
                                                    @csrf
                                                </form>
                                            @else
                                                <a href="#"
                                                   onclick="$('#flight-{{$flight["tour"]->id}}-restore').submit()"
                                                   class="btn btn-outline-warning btn-sm mb-1"><i
                                                            class="icon-magic-wand"></i></a>
                                                <form action="{{ route('flight-inventory-tours.restore', ['tour' => $tour, 'flightInventoryTour' => $flight["tour"],]) }}"
                                                      method="post" id="flight-{{$flight["tour"]->id}}-restore">
                                                    @csrf
                                                </form>
                                            @endif
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                {{-- Transports Table --}}
                <div id="transports" role="tabpanel" class="tab-pane fade">
                    <div id="transports-details">
                        <table id="transports-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Bookable?</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($transports as $transport)
                                <tr>
                                    <td style="min-width: 200px">{{ f_datetime($transport["inventory"]->departs_at) }}
                                        to {{ f_datetime($transport["inventory"]->arrives_at) }}</td>
                                    <td>{{ $transport["component"]->name }}</td>
                                    <td>{{ $transport["inventory"]->travelClass->name }}</td>
                                    <td>
                                        @if($transport["tour"]->tour_component_type == 'Upgrade')
                                            <abbr title="{{ $transport["tour"]->parent() }}">
                                                @endif
                                                {{ $transport["tour"]->tour_component_type }}
                                                @if($transport["tour"]->tour_component_type == 'Upgrade')
                                            </abbr>
                                        @endif
                                    </td>
                                    <td>{{ f_bool($transport["tour"]->is_bookable) }}</td>
                                    <td class="actions-3">
                                        @can('update', \App\Models\Transport\TransportInventoryTour::class)
                                            @if($transport["tour"]->tour_component_type !== 'Add-on')
                                                <a href="{{ route('transport-upgrade.view', ['tour' => $tour, 'inventoryTour' => $transport["tour"]->tour_component_type == 'Upgrade' ? $transport["tour"]->parent() : $transport["tour"],]) }}"
                                                   class="btn btn-outline-success btn-sm mb-1"><i
                                                            class="icon-arrow-up"></i></a>
                                            @else
                                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                                </span>
                                            @endif
                                            <a href="{{ route('transport-inventory-tours.edit', ['tour' => $tour, 'transportInventoryTour' => $transport["tour"],]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                    <i class="icon-arrow-up"></i>
                                            </span>
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Transport\TransportInventoryTour::class)
                                            @if($transport["tour"]->is_bookable)
                                                <a href="#"
                                                   onclick="$('#transport-{{$transport["tour"]->id}}-delete').submit()"
                                                   class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                                <form action="{{ route('transport-inventory-tours.delete', ['tour' => $tour, 'transportInventoryTour' => $transport["tour"],]) }}"
                                                      method="post" id="transport-{{$transport["tour"]->id}}-delete">
                                                    @csrf
                                                </form>
                                            @else
                                                <a href="#"
                                                   onclick="$('#transport-{{$transport["tour"]->id}}-restore').submit()"
                                                   class="btn btn-outline-warning btn-sm mb-1"><i
                                                            class="icon-magic-wand"></i></a>
                                                <form action="{{ route('transport-inventory-tours.restore', ['tour' => $tour, 'transportInventoryTour' => $transport["tour"],]) }}"
                                                      method="post" id="transport-{{$transport["tour"]->id}}-restore">
                                                    @csrf
                                                </form>
                                            @endif
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Payment Installment Section --}}
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Room Availability</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="templates-table" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Template</th>
                    <th scope="col">Available</th>
                </tr>
                </thead>
                @foreach($tour->getAccommodationTemplateData() as $templateData)
                    <tr>
                        <th scope="row">{{ f_date($templateData['template']->inventory->check_in->clone()->setTime(0,0,0)) }}</th>
                        <td>{{ $templateData['template'] }}</td>
                        <td>{{ implode(', ', $templateData['available']) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    {{-- Payment Installment Section --}}
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Payment Installments</h2>
    </div>
    <div class="card">
        <div class="card-body text-end">
            <a href="{{ route('payment-installments.create', ['tour' => $tour,]) }}" class="btn btn-primary">
                <i class="icon-plus"></i>
                <span>Create</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="installments-table" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Amount Due</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tr>
                    <th scope="row">Deposit</th>
                    <td>With Order</td>
                    <td>{{ f_currency($tour->deposit) }} ({{ $tour->deposit_percentage }}%)</td>
                    <td>
                        <a href="{{route('tours.edit', ['tour' => $tour,])}}"
                           class="btn btn-outline-success btn-sm mb-1">
                            <i class="icon-note"></i>
                        </a>
                    </td>
                </tr>
                @foreach($tour->paymentInstallments as $installment)
                    <tr>
                        <th scope="row">Installment</th>
                        <td>{{ f_date($installment->due_on) }}</td>
                        <td>{{ f_currency($installment->cost) }} ({{ $installment->percentage }}%)</td>
                        <td class="actions">

                            <a href="{{route('payment-installments.edit', ['tour' => $tour, 'paymentInstallment' => $installment,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-note"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('paymentInstallment-{{ $installment->id }}-delete').submit();">
                                <i class="icon-trash"></i>
                            </a>
                            <form id="paymentInstallment-{{ $installment->id }}-delete"
                                  action="{{ route('payment-installments.delete', ['tour' => $tour, 'paymentInstallment' => $installment,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th scope="row">Remaining Balance</th>
                    <td>{{ f_date($tour->final_payment) }}</td>
                    <td>{{ f_currency($tour->remaining_installment) }} ({{ $tour->remaining_percentage }}%)</td>
                    <td>
                        <a href="{{route('tours.edit', ['tour' => $tour,])}}"
                           class="btn btn-outline-success btn-sm mb-1">
                            <i class="icon-note"></i>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    {{-- Merchandise Section --}}
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Merchandise</h2>
    </div>
    <div class="card">
        <div class="card-body text-end">
            <a href="{{ route('merchandise.create', ['tour' => $tour,]) }}" class="btn btn-primary">
                <i class="icon-plus"></i>
                <span>Create</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="merchandise-table" class="table table-striped table-responsive-sm">
                <thead>
                <tr>
                    <th scope="col">Icon</th>
                    <th scope="col">Name</th>
                    <th scope="col">Variant</th>
                    <th scope="col">Size</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Sales Price</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($tour->merchandise as $merchandise)
                    <tr>
                        <td><img src="{{ $merchandise->inventory->asset }}" class="image tiny"/></td>
                        <td style="min-width: 100px">{{ $merchandise->inventory->component->name }}</td>
                        <td>{{ $merchandise->inventory->variant->name }}</td>
                        <td>{{ $merchandise->inventory->size?->name ?? 'No Size'  }}</td>
                        <td>{{ $merchandise->tour_component_type }}</td>
                        <td>{{ f_currency($merchandise->tour_sales_price) }}</td>
                        <td>{{ $merchandise->notes }}</td>
                        <td class="actions">
                            @can('update', \App\Models\Merchandise\Merchandise::class)
                                <a href="{{ route('merchandise.inventory.tour.edit', ['tour' => $tour, 'inventoryTour' => $merchandise,]) }}"
                                   class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                            <i class="icon-trash"></i>
                                        </span>
                            @endcan
                            @can('delete', \App\Models\Merchandise\Merchandise::class)
                                <a href="#" onclick="$('#merchandise-{{$merchandise->id}}-delete').submit()"
                                   class="btn btn-outline-{{ $merchandise->is_bookable ? 'danger' : 'warning' }} btn-sm mb-1">
                                    <i class="icon-{{ $merchandise->is_bookable ? 'trash' : 'magic-wand' }}"></i>
                                </a>
                                <form action="{{ route($merchandise->is_bookable ? 'merchandise.inventory.tour.delete' : 'merchandise.inventory.tour.restore',['tour' => $tour, 'inventoryTour' => $merchandise,]) }}"
                                      method="post" id="merchandise-{{$merchandise->id}}-delete">
                                    @csrf
                                </form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-trash"></i>
                                </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Orders</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="orders-table" class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Booking Reference</th>
                    <th scope="col">Lead Booker</th>
                    <th scope="col">Customers</th>
                    <th scope="col">Order Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($tour->orders as $order)
                    <tr>
                        <th scope="row"><a href="{{route('orders.view', ['order' => $order,])}}"
                                           class="link link-primary">{{ $order->booking_reference }}</a></th>
                        <td>{{ $order->leadBooker->customer->first_name . ' ' . $order->leadBooker->customer->last_name }}</td>
                        <td>{{ sizeof($order->orderCustomers) }}</td>
                        <td>
                            <h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
                        </td>
                        <td class="actions">
                            <a href="{{route('orders.edit', ['order' => $order,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-note"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Terms and Conditions</h2>
    </div>
    <div class="card">
        <div class="card-body">
            {!! $tour->terms !!}
        </div>
    </div>
@endsection
