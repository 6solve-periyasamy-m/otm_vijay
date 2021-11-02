@extends('layout.master')

@section('title', 'View Tour')

@section('head-script')
    <script>
        $(document).ready( function () {
            $('#accommodation-table').DataTable({fixedHeader: true});
            $('#activities-table').DataTable({fixedHeader: true});
            $('#flights-table').DataTable({fixedHeader: true});
            $('#transports-table').DataTable({fixedHeader: true});
            $('#installments-table').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $tour->name }}</h4>
            </div>
            <div class="col-12">
                <p>Event</p>
                <h6 class="fw-bold">{{ isset($tour->event) ? $tour->event->name : "None" }}</h6>
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
                <h6 class="fw-bold">{{ $tour->date_from }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>To</p>
                <h6 class="fw-bold">{{ $tour->date_to }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Margin</p>
                <h6 class="fw-bold">{{ $tour->margin }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Is Active</p>
                <h6 class="fw-bold">{{ $tour->is_active ? "Yes" : "No" }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Internal Notes</p>
                <h6 class="fw-bold">{{ $tour->notes }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>External Notes</p>
                <h6 class="fw-bold">{{ $tour->notes }}</h6>
            </div>                
            <div class="col-12 col-xl-6">
                <p>Description</p>
                <h6 class="fw-bold">{{ $tour->description }}</h6>
            </div>        
        </div>
    </div>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>    
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
                        Transports
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
                                <th scope="col">Component Type</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($accommodation as $accommodationEntry)
                                <tr>
                                    <td style="min-width: 200px">{{ $accommodationEntry["inventory"]->check_in }} to {{ $accommodationEntry["inventory"]->check_out }}</td>
                                    <td>{{ $accommodationEntry["component"]->title }}</td>
                                    <td>{{ $accommodationEntry["inventory"]->roomType->name }}</td>
                                    <td>{{ $accommodationEntry["tour"]->tour_component_type }}</td>
                                    <td>
                                        <a href="{{ route('accommodation-inventory-tours.edit', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationEntry["tour"],]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                        <form action="{{ route('accommodation-inventory-tours.delete', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationEntry["tour"],]) }}" method="post">
                                            @csrf
                                        </form>
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
                                <th scope="col">Component Type</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($activities as $activity)
                                <tr>
                                    <td style="min-width: 200px">{{ $activity["inventory"]->starts_at }} to {{ $activity["inventory"]->ends_at }}</td>
                                    <td>{{ $activity["component"]->title }}</td>
                                    <td>{{ $activity["component"]->activityType->name }}</td>
                                    <td>{{ $activity["tour"]->tour_component_type }}</td>
                                    <td>
                                        <a href="{{ route('activity-inventory-tours.edit', ['tour' => $tour, 'activityInventoryTour' => $activity["tour"],]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                        <form action="{{ route('activity-inventory-tours.delete', ['tour' => $tour, 'activityInventoryTour' => $activity["tour"],]) }}" method="post">
                                            @csrf
                                        </form>
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
                                <th scope="col">Component Type</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($flights as $flight)
                                <tr>
                                    <td style="min-width: 200px">{{ $flight["inventory"]->departs_at }} to {{ $flight["inventory"]->arrives_at }}</td>
                                    <td>{{ $flight["inventory"]->flight_number }}</td>
                                    <td>{{ $flight["inventory"]->travelClass->name }}</td>
                                    <td>{{ $flight["tour"]->tour_component_type }}</td>
                                    <td>
                                        <a href="{{ route('flight-inventory-tours.edit', ['tour' => $tour, 'flightInventoryTour' => $flight["tour"],]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                        <form action="{{ route('flight-inventory-tours.delete', ['tour' => $tour, 'flightInventoryTour' => $flight["tour"],]) }}" method="post">
                                            @csrf
                                        </form>
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
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($transports as $transport)
                                <tr>
                                    <td style="min-width: 200px">{{ $transport["inventory"]->departs_at }} to {{ $transport["inventory"]->arrives_at }}</td>
                                    <td>{{ $transport["component"]->name }}</td>
                                    <td>{{ $transport["inventory"]->travelClass->name }}</td>
                                    <td>{{ $transport["tour"]->tour_component_type }}</td>
                                    <td>
                                        <a href="{{ route('transport-inventory-tours.edit', ['tour' => $tour, 'transportInventoryTour' => $transport["tour"],]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                        <form action="{{ route('transport-inventory-tours.delete', ['tour' => $tour, 'transportInventoryTour' => $transport["tour"],]) }}" method="post">
                                            @csrf
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
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
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
                    <th scope="col">Due Date</th>
                    <th scope="col">Amount Due</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($tour->paymentInstallments as $installment)
                    <tr>
                        <td>{{ $installment->due_on }}</td>
                        <td>{{ $installment->amount }}</td>
                        <td>
                            <a href="{{route('payment-installments.edit', ['tour' => $tour, 'paymentInstallment' => $installment,])}}" class="btn btn-outline-success btn-sm mb-1">
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
            </table>
        </div>
    </div>
@endsection
