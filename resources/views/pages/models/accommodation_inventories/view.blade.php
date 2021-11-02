@extends('layout.main')

@section('title', 'View Accommodation Inventory')

@section('content')
    Accommodation Id: {{ $accommodationInventory->accommodation_id }}<br/>
    Room Type Id: {{ $accommodationInventory->room_type_id }}<br/>
    Board Type Id: {{ $accommodationInventory->board_type_id }}<br/>
    Check In Date Time: {{ $accommodationInventory->check_in }}<br/>
    Checkin Confirmed: {{ $accommodationInventory->check_in_time_confirmed }}<br/>
    Check Out: {{ $accommodationInventory->check_out }}<br/>
    Checkout Confirmed: {{ $accommodationInventory->check_out_time_confirmed }}<br/>
    Fit Selectable: {{ $accommodationInventory->fit_selectable }}<br/>
    Stock: {{ $accommodationInventory->stock }}<br/>
    Purchase Price: {{ $accommodationInventory->purchase_price }}<br/>
    Sales Price: {{ $accommodationInventory->sales_price }}<br/>
    Notes: {{ $accommodationInventory->notes }}<br/>
@endsection
