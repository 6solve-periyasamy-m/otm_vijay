@extends('layout.main')

@section('title', 'View Transport Inventory Tour')

@section('content')
    Tour Id: {{ $transportInventoryTour->tour_id }}<br/>
    Transport Inventory Id: {{ $transportInventoryTour->transport_inventory_id }}<br/>
@endsection
