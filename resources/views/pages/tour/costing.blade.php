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
            $('.data-table').DataTable({fixedHeader: true,});
        });
    </script>
    <style>
        .inactive {
            background-color: #ccbbcc !important;
        }
    </style>
@endsection

@section('content')
    <div class="otm-callout @if(!$tour->is_active) inactive @endif">
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
            <div class="col-12">
                <a class="btn btn-warning" href="{{route('tours.view', ['tour' => $tour,])}}">
                    <i class="icon-action-redo"></i>
                    <span>Back To Tour</span>
                </a>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped data-table">
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
    <x-admin.section.card>
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#summary">
                    <i class="icon-list"></i> {{ __('tours.costing.view.cards.components.tabs.summary') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accommodation">
                    <i class="icon-home"></i> {{ __('tours.costing.view.cards.components.tabs.accommodation') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    <i class="icon-settings"></i> {{ __('tours.costing.view.cards.components.tabs.activities') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    <i class="icon-plane"></i> {{ __('tours.costing.view.cards.components.tabs.flights') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transport">
                    <i class="icon-directions"></i> {{ __('tours.costing.view.cards.components.tabs.transport') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#extras">
                    <i class="icon-briefcase"></i> {{ __('tours.costing.view.cards.components.tabs.extras') }}
                </button>
            </li>
        </ul>
        <div id="tables" class="tab-content otm-tab-content">
            <div id="summary" role="tabpanel" class="tab-pane fade show active">
                <table class="table table-striped summary data-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tour->repository->getComponents() as $componentRepository)
                        <tr>
                            <td>
                                {{ ucwords($componentRepository->getComponentType()) }}
                            </td>
                            <td>
                                @if ($componentRepository->getComponentType() == 'merchandise')
                                    {{ __('tours.costing.view.cards.components.common.na') }}
                                @else
                                    {{ f_datetime($componentRepository->getInventory()->getStartTime()) }}
                                    to
                                    {{ f_datetime($componentRepository->getInventory()->getEndTime()) }}
                                @endif
                            </td>
                            <td>
                                {{ $componentRepository->__toString() }}
                            </td>
                            <td>
                                {{ $componentRepository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $componentRepository->getPurchasePrice() !== null ? f_currency($componentRepository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $componentRepository->getCost() !== null ? f_currency($componentRepository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $componentRepository->getMargin() !== null ? $componentRepository->getMargin() . '%' : 'No Cost to Company' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="accommodation" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary data-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tour->accommodationInventoryTours()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="activities" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary data-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tour->activityInventoryTours()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="flights" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary data-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tour->flightInventoryTours()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="transport" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary data-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tour->transportInventoryTours()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="extras" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary data-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tour->merchandise()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-admin.section.card>
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Expected Installment Revenue</h4>
                    </div>
                    <table class="table table-striped data-table">
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
                    <table class="table table-striped data-table">
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
