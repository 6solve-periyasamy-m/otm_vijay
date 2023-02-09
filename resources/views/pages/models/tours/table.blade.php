@extends('layout.master')

@section('title', 'All Tours')

@section('footer-script')
<script type="text/javascript">
    $(document).ready( function () { $('#tour').DataTable({fixedHeader: true}); });
</script>
@endsection

@section('content')
    <div class='card'>
        <div class="card-body">
            <a class="btn btn-success float-end" href="{{ route('tours.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class='card-body'>
            <table id="tour" style="width: 100%;" class="table table-striped">
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
                        <td>{{ f_currency($tour->deposit) }}</td>
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
                            <a href="{{route('tours.duplicate', ['tour' => $tour,])}}" class="btn btn-outline-info btn-sm mb-1">
                                {{ Icon::copy() }}
                            </a>
                            <a href="{{route('tours.edit', ['tour' => $tour,])}}" class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                            <a href="#" onclick="event.preventDefault();document.getElementById('tour-{{ $tour->id }}-delete').submit();" class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form id="tour-{{ $tour->id }}-delete" action="{{ route('tours.delete', ['tour' => $tour,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
