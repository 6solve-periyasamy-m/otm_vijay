@extends('layout.master')

@section('title', 'Update Accommodation Inventory')

@section('content')
    @include('partials.models.accommodation_inventories.form', ['action' => route('accommodation-inventories.update', ['accommodation' => $accommodation, 'accommodationInventory' => $accommodationInventory,]),
      'accommodation_id' => $accommodationInventory->accommodation_id,
      'room_type_id' => $accommodationInventory->room_type_id,
      'board_type_id' => $accommodationInventory->board_type_id,
      'check_in' => $accommodationInventory->check_in,
      'strict_check_in' => $accommodationInventory->strict_check_in,
      'check_out' => $accommodationInventory->check_out,
      'strict_check_out' => $accommodationInventory->strict_check_out,
      'fit_selectable' => $accommodationInventory->fit_selectable,
      'stock' => $accommodationInventory->stock,
      'purchase_price' => $accommodationInventory->purchase_price,
      'sales_price' => $accommodationInventory->sales_price,
      'notes' => $accommodationInventory->notes,
    ])
@endsection
