@extends('layout.main')

@section('title', 'All Orders')

@section('content')
    <a class="btn btn-primary" href="{{ route('orders.create') }}">Create New</a>
    <table id="order" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Quote Id</th>
            <th scope="col">Tour Id</th>
            <th scope="col">Lead Booker Id</th>
            <th scope="col">Token</th>
            <th scope="col">Booking Reference</th>
            <th scope="col">Ordered On</th>
            <th scope="col">Internal Notes</th>
            <th scope="col">External Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($orders as $order)
            @include('partials.models.orders.row', [
              'order' => $order,
              'quote_id' => $order->quote_id,
              'tour_id' => $order->tour_id,
              'lead_booker_id' => $order->lead_booker_id,
              'token' => $order->token,
              'booking_reference' => $order->booking_reference,
              'ordered_on' => $order->ordered_on,
              'internal_notes' => $order->internal_notes,
              'external_notes' => $order->external_notes,
            ])
        @endforeach
    </table>
@endsection
