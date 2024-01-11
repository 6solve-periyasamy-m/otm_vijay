@extends('layout.master')

@section('title', 'View Report')

@section('content')
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ $csv }}" style="margin-right: 5px">
            {{ Icon::csv() }}
            <span>Export to CSV</span>
        </a>
        <a class="btn btn-info float-end" href="{{ $xlsx }}" style="margin-right: 5px">
            {{ Icon::excel() }}
            <span>Export to XLSX</span>
        </a>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <div class="col-3">Passengers: {{ $data->passengers }}</div>
            <div class="col-3">Revenue (Gross Invoice Value): {{ f_currency($data->revenue) }}</div>
            <div class="col-3">Balance Paid: {{ f_currency($data->paid) }}</div>
            <div class="col-3">Remaining Value: {{ f_currency($data->remaining) }}</div>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <table class="datatable table table-striped" id="report">
            <thead>
            <tr>
                <td>Order Date</td>
                <td>Booking Reference</td>
                <td>Lead Booker</td>
                <td>Passengers</td>
                <td>Tour Name</td>
                <td>Total Order Value</td>
                <td>Balance Paid</td>
                <td>Balance Outstanding</td>
            </tr>
            </thead>
            <tbody>
            @foreach($data->orders as $order)
                <tr>
                    <td>{{ f_datetime($order->ordered_on) }}</td>
                    <td>{{ $order->booking_reference }}</td>
                    <td>{{ $order->lead_booker_name }}</td>
                    <td>{{ $order->cancelled ? 'Cancelled' : $order->customer_count }}</td>
                    <td>{{ $order->tour->name }}</td>
                    <td>{{ f_currency($order->total) }}</td>
                    <td>{{ f_currency($order->paid) }}</td>
                    <td>{{ f_currency($order->remaining) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-admin.section.card>
@endsection
