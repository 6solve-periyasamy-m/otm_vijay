@extends('layout.master')

@section('title', 'View Event')

@php
    /**
     * @var \App\Models\Tour\Event $event
     */
$hideNoCategory = $hideNoCategory ?? false;
@endphp

@push('footer-stack')
    <script>
        $(document).ready(function () {
            $('#orders').DataTable({fixedHeader: true, order: [[0, 'desc']],});
            $('#tours').DataTable({fixedHeader: true, order: [[2, 'desc']],});
        });
    </script>
@endpush

@section('content')
    <div class="otm-callout">
        <div class="row">
            @if(isset($event->image_url))
                <div class="col-2">
                    <img src="{{ asset($event->image_url) }}" class="img-thumbnail image large">
                </div>
            @endif
            <div class="col-{{ isset($event->image_url) ? 10 : 12 }} row">
                <div class="col-12">
                    <h4 class="fw-bold">{{ $event->name }}</h4>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Booking URL</p>
                    <h6 class="fw-bold">
                        {{ $event->booking_url ?? 'No Booking URL Set' }}
                    </h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Category</p>
                    <h6 class="fw-bold">
                        {{ $event->event_category->label() }}
                    </h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>From</p>
                    <h6 class="fw-bold">{{ f_date($event->starts_at) }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>To</p>
                    <h6 class="fw-bold">{{ f_date($event->ends_at) }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Description</p>
                    <h6 class="fw-bold">{{ $event->description }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Notes</p>
                    <h6 class="fw-bold">{{ $event->notes }}</h6>
                </div>
                <div class="col-12">
                    @can('update', \App\Models\Tour\Event::class)
                        <a class="btn btn-success" href="{{route('events.edit', ['event' => $event,])}}">
                            {{ Icon::edit() }}
                            <span>Edit Event</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <hr class="splitter" />
    <div class="row">
        <div class="col-xl-6">
            <div class="heading pt-2 pb-md-3 pb-2">
                <h2 class="fw-bold">Tours</h2>
            </div>
            <x-admin.section.card>
                <div class="flex justify-end mb-2">
                    <a class="btn btn-primary" href="{{ route('events.view', ['event' => $event, 'hideNoCategory' => !($hideNoCategory)]) }}">
                        {{ Icon::eye() }} {{ $hideNoCategory ? 'Show' : 'Hide' }} Tours Without Category
                    </a>
                </div>
                <table id="tours" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Orders</th>
                        <th scope="col">Booking URL</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    @foreach($event->getTours($hideNoCategory) as $tour)
                        <tr>
                            <td>
                                <a href="{{route('tours.view', ['tour' => $tour,])}}" class="link link-primary">{{ $tour->name }}</a>
                            </td>
                            <td>{{ $tour->category === null ? 'None' : $tour->category->getDisplay() }}</td>
                            <td>{{ $tour->orders()->count() }}</td>
                            <td>
                                @if(!empty($tour->getBookingFormUrl()))
                                    <a href="{{$tour->getBookingFormUrl()}}" class="link link-primary">{{ $tour->getBookingFormUrl() }}</a>
                                @else
                                    No Booking URL Set
                                @endif
                            </td>
                            <td class="actions">
                                <a href="{{route('tours.edit', ['tour' => $tour,])}}"
                                   class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
            <div class="heading pt-2 pb-md-3 pb-2">
                <h2 class="fw-bold">Orders</h2>
            </div>
            <x-admin.section.card>
                <table id="orders" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Ordered On</th>
                            <th scope="col">Booking Reference</th>
                            <th scope="col">Tour</th>
                            <th scope="col">Travelling</th>
                            <th scope="col">Order Status</th>
                        </tr>
                    </thead>
                    @foreach($event->orders as $order)
                        @php $count = $order->orderCustomers()->count() - 1; @endphp
                        <tr>
                            <td data-sort="{{$order->ordered_on->unix()}}">{{ f_datetime($order->ordered_on) }}</td>
                            <td>
                                <a href="{{route('orders.view', ['order' => $order,])}}" class="link link-primary">{{ $order->booking_reference }}</a>
                            </td>
                            <td>
                                <a href="{{route('tours.view', ['tour' => $order->tour,])}}" class="link link-primary">{{ $order->tour->name }}</a>
                            </td>
                            <td>{{ $order->leadBooker?->customer_name }}{{ $count > 0 ? " + $count" : '' }}</td>
                            <td>
                                <h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            {{-- Linked Activities --}}
            <div class="heading pt-2 pb-md-3 pb-2">
                <h2 class="fw-bold">Linked Activities</h2>
            </div>
            <x-admin.section.card>
                <table class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th scope="col">Activity</th>
                            <th scope="col">Type</th>
                            <th scope="col">Total Stock</th>
                            <th scope="col">Used Stock</th>
                            <th scope="col">Available Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->repository->getActivityReport() as $row)
                            <tr>
                                <th scope="row">{{ $row->activity }}</th>
                                <td>{{ $row->type }}</td>
                                <td>{{ $row->totalStock }}</td>
                                <td>{{ $row->usedStock }}</td>
                                <td>{{ $row->totalStock - $row->usedStock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
    </div>
@endsection
