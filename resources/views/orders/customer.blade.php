@extends('layout.main')

@section('content')
<script type="text/javascript">
function hide(table) {
    let element = document.getElementById(table + "-table");
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}
</script>
<div style="padding-left: 5%; padding-right: 5%; padding-top: 0.1%;">
<div class="text-dark" style="padding: 1% 100px; border: 5px solid black; border-radius: 25px;">
    {{-- Header Details --}}
    <div id="header-details">
        <table class="table" style="border-bottom: 1px solid black; font-size: 32px">
            <tr>
                <td style="border: 1px solid black; font-size: 24px">{{ $order->booking_reference }}</td>
                <td style="border: 1px solid black; font-size: 24px">{{ $order->tour->title }}</td>
                <td style="border: 1px solid black; font-size: 24px">{{ $order->tour->date_from . " to " . $order->tour->date_to }}</td>
                <td style="border: 1px solid black; background-color: {{ true ? "#33ff99" : "#ffff99" }}; font-size: 24px">{{ true ? "Paid in Full" : "Balance Outstanding" }}</td>
            </tr>
        </table>
    </div>
    {{-- Customer Details Section --}}
    <div id="section-1" style="margin-bottom: 5px; border: 1px solid black;">
        <table class="table table-striped" style="min-width: 40%; max-width: 40%; display:inline-block; vertical-align: bottom; horiz-align: left">
            <thead>
            <tr>
                <th scope="col">Detail</th>
                <th scope="col">Value</th>
            </tr>
            </thead>
            <tr>
                <th scope="row">Full Name:</th>
                <td>{{ $customer->first_name }} {{ $customer->middle_names ?? "" }} {{ $customer->last_name }}</td>
            </tr>
            <tr>
                <th scope="row">Date of Birth:</th>
                <td>{{ $customer->date_of_birth }}</td>
            </tr>
            <tr>
                <th scope="row">Passport Number:</th>
                <td>{{ $customer->passport_number }}</td>
            </tr>
            <tr>
                <th scope="row">Full Name:</th>
                <td>{{ $customer->passport_expiry_date }}</td>
            </tr>
        </table>
        <table class="table table-striped" style="min-width: 40%; max-width: 40%; display:inline-block; vertical-align: bottom; horiz-align: right">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Home Address</th>
                    <th scope="col">Billing Address</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">Street Address</th>
                <td>{{ $customer->address_line_1 }}, {{ $customer->address_line_2 }}, {{ $customer->address_line_3 }}</td>
                <td>{{ $customer->billing_line_1 }}, {{ $customer->billing_line_2 }}, {{ $customer->billing_line_3 }}</td>
            </tr>
            <tr>
                <th scope="row">Town</th>
                <td>{{ $customer->town }}</td>
                <td>{{ $customer->billing_town }}</td>
            </tr>
            <tr>
                <th scope="row">Country</th>
                <td>{{ $customer->country }}</td>
                <td>{{ $customer->billing_country }}</td>
            </tr>
            <tr>
                <th scope="row">Postcode</th>
                <td>{{ $customer->postcode }}</td>
                <td>{{ $customer->billing_postcode }}</td>
            </tr>
        </table>
    </div>
    <div id="billing-section" style="border: 1px solid black">
        <div id="billing-header" style="max-width: 20%; font-size: 24px; border: 1px solid black; border-top: 0; border-left: 0; margin-bottom: 2px;">
            Tour Components
        </div>
        {{-- Accommodation Table --}}
        <div id="components-section">
            <div id="accommodation-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
                <div id="accommodation-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                    <a href="javascript:hide('accommodation');" class="link-secondary">Accommodations</a>
                </div>
                <table id="accommodation-table" class="table table-striped" style="min-width: 100%; margin-bottom: 1px; border: 1px solid black">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Room Type</th>
                        <th scope="col">Shared With</th>
                        <th scope="col">Component Type</th>
                    </tr>
                    </thead>
                    @foreach($accommodation as $accommodationEntry)
                        <tr>
                            <td>{{ $accommodationEntry["inventory"]->check_in_date_time }} to {{ $accommodationEntry["inventory"]->check_out_date_time }}</td>
                            <td>{{ $accommodationEntry["component"]->title }}</td>
                            <td>{{ $accommodationEntry["inventory"]->roomType->room_type_name }}</td>
                            <td>TBI</td> {{-- TODO: Discuss and Implement--}}
                            <td>{{ $accommodationEntry["tour"]->tour_component_type }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{-- Activities Table --}}
        <div id="components-section">
            <div id="activities-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
                <div id="activities-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                    <a href="javascript:hide('activities');" class="link-secondary">Activities</a>
                </div>
                <table id="activities-table" class="table table-striped" style="min-width: 100%; margin-bottom: 1px; border: 1px solid black">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Activity Type</th>
                        <th scope="col">Component Type</th>
                    </tr>
                    </thead>
                    @foreach($activities as $activity)
                        <tr>
                            <td>{{ $activity["inventory"]->activity_start_date_time }} to {{ $activity["inventory"]->activity_end_date_time }}</td>
                            <td>{{ $activity["component"]->title }}</td>
                            <td>{{ $activity["component"]->activityType->activity_type_title }}</td>
                            <td>{{ $activity["tour"]->tour_component_type }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{-- Flights Table --}}
        <div id="components-section">
            <div id="activities-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
                <div id="activities-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                    <a href="javascript:hide('flights');" class="link-secondary">Flights</a>
                </div>
                <table id="flights-table" class="table table-striped" style="min-width: 100%; margin-bottom: 1px; border: 1px solid black">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Travel Class</th>
                        <th scope="col">Component Type</th>
                    </tr>
                    </thead>
                    @foreach($flights as $flight)
                        <tr>
                            <td>{{ $flight["inventory"]->departure_date_time }} to {{ $flight["inventory"]->arrival_date_time }}</td>
                            <td>{{ $flight["inventory"]->flight_number }}</td>
                            <td>{{ $flight["inventory"]->travelClass->title }}</td>
                            <td>{{ $flight["tour"]->tour_component_type }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{-- Transports Table --}}
        <div id="components-section">
            <div id="activities-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
                <div id="activities-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                    <a href="javascript:hide('transports');" class="link-secondary">Transports</a>
                </div>
                <table id="transports-table" class="table table-striped" style="min-width: 100%; margin-bottom: 1px; border: 1px solid black">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Travel Class</th>
                        <th scope="col">Component Type</th>
                    </tr>
                    </thead>
                    @foreach($transports as $transport)
                        <tr>
                            <td>{{ $transport["inventory"]->departure_date_time }} to {{ $transport["inventory"]->arrival_date_time }}</td>
                            <td>{{ $transport["component"]->name }}</td>
                            <td>{{ $transport["inventory"]->travelClass->title }}</td>
                            <td>{{ $transport["tour"]->tour_component_type }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    {{-- Closing Container---}}
</div>
</div>
@endsection