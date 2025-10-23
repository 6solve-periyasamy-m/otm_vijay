@php use App\Helpers\ActivitySortFilter; @endphp
@extends('layout.master')

@section('title', 'View Event')

@php
/**
 * @var \App\Models\Tour\Event $event
 */
$hideNoCategory = $hideNoCategory ?? false;
if (!($activityFilter instanceof ActivitySortFilter)) {
    $activityFilter = ActivitySortFilter::from($activityFilter);
}
@endphp

@push('footer-stack')
    <script>
        $(document).ready(function () {
            $('#orders').DataTable({fixedHeader: true, order: [[0, 'desc']],});
            $('#tours').DataTable({fixedHeader: true, order: [[2, 'desc']],});
            $('#linked-activities').DataTable({fixedHeader: true, order: [[3, 'asc']],});
        });

        function change_filter(obj) {
            let url = "{{ route('events.view', ['event' => $event,]) }}?activityFilter=";
            window.location.href = url + obj.value;
        }
    </script>
@endpush

@section('content')
    @if($event->banner_url !== null)
        <div class="d-block" style="padding: 1rem;">
            <img src="{{ asset($event->banner_url) }}" height="100" style="max-height: 100px; min-width: 100%;"
                 alt="Event Banner"/>
        </div>
    @endif
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
                    @can('create', \App\Models\Tour\Event::class)
                        <a class="btn btn-secondary" href="{{route('events.duplicate', ['event' => $event,])}}">
                            {{ Icon::copy() }}
                            <span>Duplicate Event</span>
                        </a>
                    @endcan
                    <a class="btn btn-warning" href="{{ route('events.reminder.bulk', ['event' => $event,]) }}">
                        {{ Icon::calendar() }}
                        <span>Bulk Send Reminders</span>
                    </a>
                    <a class="btn btn-info" href="{{ route('events.manifest.order.view', ['event' => $event]) }}">
                        {{ Icon::report() }}
                        <span>View Order Manifest</span>
                    </a>
                    <a class="btn btn-info" href="{{ route('events.report.hotel-stock', ['event' => $event]) }}">
                        {{ Icon::report() }}
                        <span>View Hotel Stock</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-{{ $event->event_category === \App\Models\Helper\Enum\EventType::MAIN ? 12 : 6 }}">
            <div class="heading pt-2 pb-md-3 pb-2">
                <h2 class="fw-bold">Tours</h2>
            </div>
            <x-admin.section.card>
                <div class="flex justify-end mb-2">
                    <a class="btn btn-primary"
                       href="{{ route('events.view', ['event' => $event, 'hideNoCategory' => !($hideNoCategory)]) }}">
                        {{ Icon::eye() }} {{ $hideNoCategory ? 'Show' : 'Hide' }} Tours Without Category
                    </a>
                </div>
                <table id="tours" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Orders</th>
                        <th scope="col" style="width: 5em;">Booking URL</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    @foreach($event->getTours($hideNoCategory) as $tour)
                        <tr>
                            <td>
                                <a href="{{route('tours.view', ['tour' => $tour,])}}"
                                   class="link link-primary">{{ $tour->name }}</a>
                            </td>
                            <td>{{ $tour->category === null ? 'None' : $tour->category->getDisplay() }}</td>
                            <td>{{ $tour->orders()->count() }}</td>
                            <td class="text-break" style="word-break: break-word; max-width: 180px;">
                                @if(!empty($tour->getBookingFormUrl()))
                                    <a href="{{$tour->getBookingFormUrl()}}"
                                       class="link link-primary d-inline-block text-break">{{ $tour->getBookingFormUrl() }}</a>
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
            <x-admin.section.accordion closed color="#a3caee">
                <x-slot:title>Orders</x-slot:title>
                <x-admin.section.card>
                    <table id="orders" class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Ordered On</th>
                            <th scope="col">Booking Reference</th>
                            <th scope="col">Travelling</th>
                            <th scope="col">Order Status</th>
                        </tr>
                        </thead>
                        @foreach($event->orders as $order)
                            @php $count = $order->orderCustomers()->count() - 1; @endphp
                            <tr>
                                <td data-sort="{{$order->ordered_on->unix()}}">{{ f_datetime($order->ordered_on) }}</td>
                                <td>
                                    <a href="{{route('orders.view', ['order' => $order,])}}"
                                       class="link link-primary">{{ $order->booking_reference }}</a>
                                </td>
                                <td>{{ $order->leadBooker?->customer_name }}{{ $count > 0 ? " + $count" : '' }}</td>
                                <td>
                                    <h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </x-admin.section.card>
            </x-admin.section.accordion>
        </div>
        @if($event->event_category !== \App\Models\Helper\Enum\EventType::MAIN)
        <div class="col-xl-6">
            {{-- Linked Activities --}}
            <div class="heading pt-2 pb-md-3 pb-2">
                <h2 class="fw-bold">Linked Activities</h2>
            </div>
            <x-admin.section.card>
                <div class="row">
                    @if($event->parent === null)
                        <div class="col-12">
                            <span style="color: red">Warning: No event parent is set, so cannot locate connected activities</span>
                        </div>
                    @endif
                    <div class="col-12">
                        <x-livewire.input.dropdown name="filter" :items="ActivitySortFilter::toArray()" value="{{$activityFilter->value}}" label="Filter" onchange="change_filter(this)"/>
                    </div>
                </div>
                <table class="table table-striped" id="linked-activities">
                    <thead>
                    <tr>
                        <th scope="col">Activity</th>
                        <th scope="col">Category</th>
                            <th scope="col">Type</th>
                        <th scope="col">Total Stock</th>
                        <th scope="col">Used Stock</th>
                        <th scope="col">Available Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($event->repository->getActivityReport($activityFilter) as $row)
                        @continue($row === null)
                        <tr class="{{ ($row->totalStock - $row->usedStock) <= 0 ? 'tr-red' : '' }}">
                            <th scope="row">
                                <a href="{{ route('activities.view', ['activity' => $row->component,]) }}">{{ $row->activity }}</a>
                            </th>
                            <td>{{ $row->category }}</td>
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
        @endif
    </div>
@endsection
