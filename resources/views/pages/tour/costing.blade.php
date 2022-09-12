@php 
/** 
 * @var \App\Models\Tour\Tour $tour 
 */
$basicCtC = $tour->repository->getCosting()->getCostOfComponents(['Included']);
$fullCtC = $tour->repository->getCosting()->getMaxCostOfComponents();
$fullCustomer = $tour->repository->getCosting()->getMaxCostToCustomer();
$averageCtC = ($basicCtC+$fullCtC)/2;
$averageCustomer = ($tour->base_price_per_person+$fullCustomer)/2;
@endphp
@extends('layout.master')

@section('title', "Tour Costing")

@section('header-script')
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $tour->name }} @isset($tour->event)({{$tour->event->name}})@endisset</h4>
            </div>
            <div class="col-12 col-xl-4">
                <p>Booking URL</p>
                <h6 class="fw-bold">
                    @isset($tour->booking_form_url)
                        <a href="{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,]) }}">{{$tour->booking_form_url}}</a>
                    @else
                        No Booking URL Set
                    @endisset
                </h6>
            </div>
            <div class="col-12 col-xl-2">
                <p>Price per Person</p>
                <h6 class="fw-bold">
                    {{ f_currency($tour->base_price_per_person) }}
                </h6>
            </div>
            <div class="col-12 col-xl-2">
                <p>Surcharge</p>
                <h6 class="fw-bold">
                    {{ f_currency($tour->single_occupancy_surcharge) }}
                </h6>
            </div>
            <div class="col-12 col-xl-4">
                <p>Dates</p>
                <h6 class="fw-bold">
                    {{ f_date($tour->date_from) }} to {{ f_date($tour->date_to) }}
                </h6>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <td></td>
                    <th scope="col" class="fw-bold">Cost to Company</th>
                    <th scope="col" class="fw-bold">Margin</th>
                    <th scope="col" class="fw-bold">Cost to Customer</th>
                    <th scope="col" class="fw-bold">Profit</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Base Package</th>
                    <td>{{ f_currency($basicCtC) }}</td>
                    <td>{{ $basicCtC > 0 ? sigfig((($tour->base_price_per_person-$basicCtC)/$basicCtC)*100) . '%' : 'No Cost'}}</td>
                    <td>{{ f_currency($tour->base_price_per_person) }}</td>
                    <td>{{ f_currency($tour->base_price_per_person - $basicCtC) }}</td>
                </tr>
                <tr>
                    <th scope="row">Average (Approx.)</th>
                    <td>{{ f_currency($averageCtC) }}</td>
                    <td>{{$averageCtC > 0 ? sigfig((($averageCustomer - $averageCtC)/$averageCtC)*100) . '%' : 'No Cost' }}</td>
                    <td>{{ f_currency($averageCustomer) }}</td>
                    <td>{{ f_currency($averageCustomer - $averageCtC) }}</td>
                </tr>
                <tr>
                    <th scope="row">Full Package</th>
                    <td>{{ f_currency($fullCtC) }}</td>
                    <td>{{ $fullCtC > 0 ? sigfig((($fullCustomer-$fullCtC)/$fullCtC)*100) . '%' : 'No Cost' }}</td>
                    <td>{{ f_currency($fullCustomer) }}</td>
                    <td>{{ f_currency($fullCustomer - $fullCtC) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
