@extends('layout.master')

@section('title', 'All Tours')

@section('content')
    <div class='card'>
        <div class="card-body">
            <a class="btn btn-success float-end" href="{{ route('tours.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px;" href="{{ route('tours.all', ['historic' => !($historic ?? true),]) }}">
                <i class="icon-eye"></i>
                <span>{{ !($historic ?? true) ? "Show" : "Hide" }} Historic (Older than {{ setting('system.historic', 6) }} month(s))</span>
            </a>
        </div>
    </div>
    <x-admin.section.card>
        <table id="tour" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Event</th>
                <th scope="col">Category</th>
                <th scope="col">Description</th>
                <th scope="col">Date From</th>
                <th scope="col">Date To</th>
                <th scope="col">Base Price Per Person</th>
                <th scope="col">Deposit</th>
                <th scope="col">Stock</th>
                <th scope="col">Booking Form Url</th>
                <th scope="col">Is Active</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($tours as $tour)
                <tr>
                    <td><a href="{{ route('tours.view', ['tour' => $tour->id,]) }}">{{ $tour->name }}</a></td>
                    <td>{{ isset($tour->event) ? $tour->event->name : "No Event" }}</td>
                    <td>{{ isset($tour->category) ? $tour->category->name : "No Category" }}</td>
                    <td>{{ truncate($tour->description) }}</td>
                    <td data-sort="{{$tour->date_from->unix()}}">{{ f_date($tour->date_from) }}</td>
                    <td data-sort="{{$tour->date_to->unix()}}">{{ f_date($tour->date_to) }}</td>
                    <td>{{ f_currency($tour->base_price_per_person) }}</td>
                    <td>{{ f_currency($tour->deposit_amount) }} ({{$tour->deposit_percentage}}%)</td>
                    <td>
                        @if($tour->stock_control_active)
                            {{$tour->stock - $tour->getUsedStock()}}/{{ $tour->stock }}<br/>
                            ({{$tour->getUsedStock()}} Sold)
                        @else
                            {{$tour->getUsedStock()}} Sold
                        @endif
                    </td>
                    <td>
                        @if($tour->booking_form_url !== null)
                            <a href="{{route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url,])}}">{{ $tour->booking_form_url }}</a>
                        @else
                            No URL
                        @endif
                    </td>
                    <td>{{ $tour->is_active ? "Yes" : "No" }}</td>
                    <td class="actions-3">
                        <a href="{{route('tours.duplicate', ['tour' => $tour,])}}" title="Copy" class="btn btn-outline-info btn-sm mb-1">
                            {{ Icon::copy() }}
                        </a>
                        <a href="{{route('tours.edit', ['tour' => $tour,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                            {{ Icon::edit() }}
                        </a>
                        @if(!$tour->trashed())
                            <a href="#" title="Delete" onclick="event.preventDefault();document.getElementById('tour-{{ $tour->id }}-delete').submit();" class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form id="tour-{{ $tour->id }}-delete" action="{{ route('tours.delete', ['tour' => $tour,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <a href="#" onclick="event.preventDefault();document.getElementById('tour-{{ $tour->id }}-delete').submit();" class="btn btn-outline-warning btn-sm mb-1">
                                <i class="icon-magic-wand"></i>
                            </a>
                            <form id="tour-{{ $tour->id }}-delete" action="{{ route('tours.restore', ['tour' => $tour,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
