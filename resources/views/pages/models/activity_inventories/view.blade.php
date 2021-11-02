@extends('layout.main')

@section('title', 'View Activity Inventory')

@section('content')
    Activity Id: {{ $activityInventory->activity_id }}<br/>
    Ticket Type Id: {{ $activityInventory->ticket_type_id }}<br/>
    Activity Start Date Time: {{ $activityInventory->starts_at }}<br/>
    Activity End Date Time: {{ $activityInventory->ends_at }}<br/>
    Fit Selectable: {{ $activityInventory->fit_selectable }}<br/>
    Stock: {{ $activityInventory->stock }}<br/>
    Purchase Price: {{ $activityInventory->purchase_price }}<br/>
    Sales Price: {{ $activityInventory->sales_price }}<br/>
    Currency: {{ $activityInventory->currency }}<br/>
    Notes: {{ $activityInventory->notes }}<br/>
@endsection
