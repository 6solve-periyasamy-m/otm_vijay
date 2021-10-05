@extends('layout.main')

@section('title', 'View Activity Inventories')

@section('content')
    Activity Id: {{ $activityInventory->activity_id }}<br/>
    Ticket Type Id: {{ $activityInventory->ticket_type_id }}<br/>
    Activity Start Date Time: {{ $activityInventory->activity_start_date_time }}<br/>
    Activity End Date Time: {{ $activityInventory->activity_end_date_time }}<br/>
    Fit Selectable: {{ $activityInventory->fit_selectable }}<br/>
    Stock: {{ $activityInventory->stock }}<br/>
    Purchase Price: {{ $activityInventory->purchase_price }}<br/>
    Sales Price: {{ $activityInventory->sales_price }}<br/>
    Currency: {{ $activityInventory->currency }}<br/>
    Notes: {{ $activityInventory->notes }}<br/>
@endsection
