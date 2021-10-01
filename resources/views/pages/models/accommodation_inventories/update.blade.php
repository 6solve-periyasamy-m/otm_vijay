@extends('layout.main')

@section('title', 'Update Accommodation Inventories')

@section('content')
  @include('partials.models.accommodation_inventories.form', ['action' => route('accommodation-inventories.update', ['accommodationInventory' => $accommodationInventory,]),
    'accommodation_id' => $accommodationInventory->accommodation_id,
    'room_type_id' => $accommodationInventory->room_type_id,
    'board_type_id' => $accommodationInventory->board_type_id,
    'check_in_date_time' => $accommodationInventory->check_in_date_time,
    'checkin_confirmed' => $accommodationInventory->checkin_confirmed,
    'check_out_date_time' => $accommodationInventory->check_out_date_time,
    'checkout_confirmed' => $accommodationInventory->checkout_confirmed,
    'fit_selectable' => $accommodationInventory->fit_selectable,
    'stock' => $accommodationInventory->stock,
    'purchase_price' => $accommodationInventory->purchase_price,
    'sales_price' => $accommodationInventory->sales_price,
    'notes' => $accommodationInventory->notes,
  ])
@endsection
