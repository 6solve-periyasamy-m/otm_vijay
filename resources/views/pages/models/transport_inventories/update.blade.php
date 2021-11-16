@extends('layout.form', ['action' => route('transport-inventories.update', ['transport' => $transport, 'transportInventory' => $transportInventory,]),])

@section('title', 'Update Transport Inventory')

@section('form-body')
    @include('partials.models.transport_inventories.form', [
      'transport_id' => $transportInventory->transport_id,
      'travel_class_id' => $transportInventory->travel_class_id,
      'departs_at' => $transportInventory->departs_at,
      'departure_time_confirmed' => $transportInventory->departure_time_confirmed,
      'arrives_at' => $transportInventory->arrives_at,
      'arrival_time_confirmed' => $transportInventory->arrival_time_confirmed,
      'fit_selectable' => $transportInventory->fit_selectable,
      'stock' => $transportInventory->stock,
      'purchase_price' => $transportInventory->purchase_price,
      'sales_price' => $transportInventory->sales_price,
      'currency' => $transportInventory->currency,
      'notes' => $transportInventory->notes,
    ])
@endsection
