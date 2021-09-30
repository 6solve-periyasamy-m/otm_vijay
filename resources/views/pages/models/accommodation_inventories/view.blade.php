@extends('layout.main')

@section('title', 'View Accommodation Inventories')

@section('content')
Accommodation Id: {{ $accommodationInventory->accommodation_id }}<br />
Room Type Id: {{ $accommodationInventory->room_type_id }}<br />
Board Type Id: {{ $accommodationInventory->board_type_id }}<br />
Check In Date Time: {{ $accommodationInventory->check_in_date_time }}<br />
Checkin Confirmed: {{ $accommodationInventory->checkin_confirmed }}<br />
Check Out Date Time: {{ $accommodationInventory->check_out_date_time }}<br />
Checkout Confirmed: {{ $accommodationInventory->checkout_confirmed }}<br />
Fit Selectable: {{ $accommodationInventory->fit_selectable }}<br />
Stock: {{ $accommodationInventory->stock }}<br />
Purchase Price: {{ $accommodationInventory->purchase_price }}<br />
Sales Price: {{ $accommodationInventory->sales_price }}<br />
Notes: {{ $accommodationInventory->notes }}<br />
@endsection
