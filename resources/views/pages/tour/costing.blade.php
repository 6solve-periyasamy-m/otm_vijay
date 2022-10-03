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
            $('.installment-revenue-table').DataTable({fixedHeader: true,});
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
                    <td style="width: 20%"></td>
                    <th scope="col" class="fw-bold text-center" style="width: 20%">Cost to Company</th>
                    <th scope="col" class="fw-bold text-center" style="width: 20%">Margin</th>
                    <th scope="col" class="fw-bold text-center" style="width: 20%">Cost to Customer</th>
                    <th scope="col" class="fw-bold text-center" style="width: 20%">Profit</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row" style="width: 20%">Base Package</th>
                    <td class="text-center" style="width: 20%">{{ f_currency($basicCtC) }}</td>
                    <td class="text-center" style="width: 20%">{{ $basicCtC > 0 ? sigfig((($tour->base_price_per_person-$basicCtC)/$basicCtC)*100) . '%' : 'No Cost'}}</td>
                    <td class="text-center" style="width: 20%">{{ f_currency($tour->base_price_per_person) }}</td>
                    <td class="text-center" style="width: 20%">{{ f_currency($tour->base_price_per_person - $basicCtC) }}</td>
                </tr>
                <tr>
                    <th scope="row" style="width: 20%">Full Package - All add-ons & Upgrades</th>
                    <td class="text-center" style="width: 20%">{{ f_currency($fullCtC) }}</td>
                    <td class="text-center" style="width: 20%">{{ $fullCtC > 0 ? sigfig((($fullCustomer-$fullCtC)/$fullCtC)*100) . '%' : 'No Cost' }}</td>
                    <td class="text-center" style="width: 20%">{{ f_currency($fullCustomer) }}</td>
                    <td class="text-center" style="width: 20%">{{ f_currency($fullCustomer - $fullCtC) }}</td>
                </tr>
                <tr>
                    <th scope="row" style="width: 20%">Average (Median)</th>
                    <td class="text-center" style="width: 20%">{{ f_currency($averageCtC) }}</td>
                    <td class="text-center" style="width: 20%">{{$averageCtC > 0 ? sigfig((($averageCustomer - $averageCtC)/$averageCtC)*100) . '%' : 'No Cost' }}</td>
                    <td class="text-center" style="width: 20%">{{ f_currency($averageCustomer) }}</td>
                    <td class="text-center" style="width: 20%">{{ f_currency($averageCustomer - $averageCtC) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Expected Installment Revenue</h4>
                    </div>
                    <table class="table table-striped installment-revenue-table">
                        <thead>
                        <tr>
                            <th scope="col">Due Date</th>
                            <th scope="col">Count</th>
                            <th scope="col">Expected</th>
                            <th scope="col">Paid</th>
                            <th scope="col">Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tour->repository->getCosting()->getInstallmentData() as $row)
                            <tr>
                                <td data-order="{{ $row->date->unix() }}">{{ f_date($row->date) }}</td>
                                <td>{{ $row->count }}</td>
                                <td>{{ f_currency($row->expected) }}</td>
                                <td>{{ f_currency($row->received) }}</td>
                                <td>{{ f_currency($row->expected - $row->received) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Potential Revenue</h4>
                    </div>
                    {{ $tour->repository->getCosting()->getTourRevenueDonut() }}
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Orders</h4>
                    </div>
                    <table class="table table-striped installment-revenue-table">
                        <thead>
                        <tr>
                            <th scope="col">Booking Reference</th>
                            <th scope="col">Paying Travellers</th>
                            <th scope="col">Expected</th>
                            <th scope="col">Paid</th>
                            <th scope="col">Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tour->orders as $order)
                            <tr>
                                <td>{{ $order->booking_reference }}</td>
                                <td>{{ $order->paying_customers }}</td>
                                <td>{{ f_currency($order->cost) }}</td>
                                <td>{{ f_currency($order->paid) }}</td>
                                <td>{{ f_currency($order->remaining) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Orders Over Time</h4>
                    </div>
                    {{ $tour->repository->getCosting()->getOrdersOverTime() }}
                </div>
            </div>
        </div>
    </div>
@endsection
