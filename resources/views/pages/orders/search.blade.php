@extends('layout.main')

@section('title', 'All Orders')

@section('content')
    <div><img src="{{ asset('images/octlogo.png') }}" style="margin-left: auto; margin-right: auto; display: block; width: 30%"/></div>
    <form action="{{ route("orderSearch")}}" method="get">
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="query-input" name="query" placeholder="Search Query" value="{{ $query ?? "" }}">
            <div class="input-group-append">
                <button type="button" class="btn btn-amber" label="Search">Search</button>
            </div>
        </div>
    </form>
    <table class="table table-striped" style="border-radius: 10px;">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Order Date</th>
            <th scope="col">Lead Booker</th>
            <th scope="col">Booking Reference</th>
            <th scope="col">Tour</th>
        </tr>
        </thead>
        @foreach($data as $row)
            <tr>
                <td>{{$row->ordered_on}}</td>
                <td>{{$row->lead_booker_first_name . ' ' . $row->lead_booker_last_name }}</td>
                <td><a href="{{ route('orderDetails', ['id' => $row->order_id]) }}" class="link-info"><u>{{$row->booking_reference}}</u></a></td>
                <td>{{$row->tour_title}}</td>
            </tr>
        @endforeach
    </table>
@endsection
