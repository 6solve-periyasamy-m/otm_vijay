@extends('layout.form', ['action' => route('activity-inventories.update', ['activity' => $activity, 'activityInventory' => $activityInventory, ]),])

@section('title', 'Update Activity Inventory')

@section('form-body')
    @include('partials.models.activity_inventories.form', [
      'activity_id' => $activityInventory->activity_id,
      'ticket_type_id' => $activityInventory->ticket_type_id,
      'starts_at' => $activityInventory->starts_at,
      'ends_at' => $activityInventory->ends_at,
      'fit_selectable' => $activityInventory->fit_selectable,
      'stock' => $activityInventory->stock,
      'purchase_price' => $activityInventory->purchase_price,
      'sales_price' => $activityInventory->sales_price,
      'currency' => $activityInventory->currency,
      'notes' => $activityInventory->notes,
    ])
@endsection
