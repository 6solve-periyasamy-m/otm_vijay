@extends('layout.form', ['action' => route('accommodation-inventories.update', ['accommodation' => $accommodation, 'accommodationInventory' => $accommodationInventory,])])

@section('title', 'Update Accommodation Inventory')

@section('form-body')
    @include('partials.models.accommodation_inventories.form', [
      'accommodation_id' => $accommodationInventory->accommodation_id,
      'room_type_id' => $accommodationInventory->room_type_id,
      'board_type_id' => $accommodationInventory->board_type_id,
      'check_in' => $accommodationInventory->check_in,
      'check_in_time_confirmed' => $accommodationInventory->check_in_time_confirmed,
      'check_out' => $accommodationInventory->check_out,
      'check_out_time_confirmed' => $accommodationInventory->check_out_time_confirmed,
      'fit_selectable' => $accommodationInventory->fit_selectable,
      'stock' => $accommodationInventory->stock,
      'purchase_price' => $accommodationInventory->purchase_price,
      'sales_price' => $accommodationInventory->sales_price,
      'currency' => $accommodationInventory->currency,
      'notes' => $accommodationInventory->notes,
    ])
@endsection
