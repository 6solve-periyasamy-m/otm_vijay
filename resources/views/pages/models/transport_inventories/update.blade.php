@extends('layout.main')

@section('title', 'Update Transport Inventories')

@section('content')
    @include('partials.models.transport_inventories.form', ['action' => route('transport-inventories.update', ['transport' => $transport, 'transportInventory' => $transportInventory,]),
      'transport_id' => $transportInventory->transport_id,
      'travel_class_id' => $transportInventory->travel_class_id,
      'departure_date_time' => $transportInventory->departure_date_time,
      'departure_confirmed' => $transportInventory->departure_confirmed,
      'arrival_date_time' => $transportInventory->arrival_date_time,
      'arrival_confirmed' => $transportInventory->arrival_confirmed,
      'fit_selectable' => $transportInventory->fit_selectable,
      'stock' => $transportInventory->stock,
      'purchase_price' => $transportInventory->purchase_price,
      'sales_price' => $transportInventory->sales_price,
      'currency' => $transportInventory->currency,
      'notes' => $transportInventory->notes,
    ])
@endsection
