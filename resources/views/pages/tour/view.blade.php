@extends('layout.main')

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
    <table style="width: 100%">
        <tr>
            <td style="border: 1px solid black">{{ $tour->title }}</td>
            <td style="border: 1px solid black">Price per Person: {{ $tour->base_price_per_person }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">Event: {{ $tour->event->event_title }}</td>
            <td style="border: 1px solid black">Single Occupancy Surcharge: {{ $tour->single_occupancy_surcharge }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">From: {{ $tour->date_from }}</td>
            <td style="border: 1px solid black">To: {{ $tour->date_to }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">Margin: {{ $tour->margin }}</td>
            <td style="border: 1px solid black">Is Active: {{ $tour->is_active ? "Yes" : "No" }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black">Internal Notes: {{ $tour->notes }}</td>
            <td style="border: 1px solid black">External Notes: {{ $tour->notes }}</td>
        </tr>
    </table>
    {{ $tour->description }}
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <h1>Components</h1>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    {{-- Tabs Definition --}}
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">Accommodation</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">Activities</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">Flights</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">Transports</button>
        </li>
    </ul>
    {{-- Tables Definition --}}
    <div id="tables" class="tab-content" style="padding: 5px">
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
                            <td>{{ $accommodationEntry["inventory"]->check_in_date_time }} to {{ $accommodationEntry["inventory"]->check_out_date_time }}</td>
                            <td>{{ $accommodationEntry["component"]->title }}</td>
                            <td>{{ $accommodationEntry["inventory"]->roomType->room_type_name }}</td>
                            <td>{{ $accommodationEntry["tour"]->tour_component_type }}</td>
                            <td>
                                <a href="{{ route('accommodation-inventory-tours.edit', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationEntry["tour"],]) }}" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon></a>
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
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
                            <td>{{ $activity["inventory"]->activity_start_date_time }} to {{ $activity["inventory"]->activity_end_date_time }}</td>
                            <td>{{ $activity["component"]->title }}</td>
                            <td>{{ $activity["component"]->activityType->name }}</td>
                            <td>{{ $activity["tour"]->tour_component_type }}</td>
                            <td>
                                <a href="{{ route('activity-inventory-tours.edit', ['tour' => $tour, 'activityInventoryTour' => $activity["tour"],]) }}" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon></a>
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
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
                            <td>{{ $flight["inventory"]->departure_date_time }} to {{ $flight["inventory"]->arrival_date_time }}</td>
                            <td>{{ $flight["inventory"]->flight_number }}</td>
                            <td>{{ $flight["inventory"]->travelClass->title }}</td>
                            <td>{{ $flight["tour"]->tour_component_type }}</td>
                            <td>
                                <a href="{{ route('flight-inventory-tours.edit', ['tour' => $tour, 'flightInventoryTour' => $flight["tour"],]) }}" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon></a>
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
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
                            <td>{{ $transport["inventory"]->departure_date_time }} to {{ $transport["inventory"]->arrival_date_time }}</td>
                            <td>{{ $transport["component"]->name }}</td>
                            <td>{{ $transport["inventory"]->travelClass->title }}</td>
                            <td>{{ $transport["tour"]->tour_component_type }}</td>
                            <td>
                                <a href="{{ route('transport-inventory-tours.edit', ['tour' => $tour, 'transportInventoryTour' => $transport["tour"],]) }}" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon></a>
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
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
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <h1>Payment Installments</h1>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <table id="installments-table" class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Due Date</th>
            <th scope="col">Amount Due</th>
        </tr>
        </thead>
        @foreach($tour->paymentInstallments as $installment)
            <tr>
                <td>{{ $installment->due_on }}</td>
                <td>{{ $installment->amount }}</td>
            </tr>
        @endforeach
    </table>
@endsection
