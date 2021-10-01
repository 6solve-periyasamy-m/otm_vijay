@extends('layout.main')

@section('title', 'Update Activity Inventories')

@section('content')
    @include('partials.models.activity_inventories.form', ['action' => route('activity-inventories.update', ['activityInventory' => $activityInventory,]),
      'activity_id' => $activityInventory->activity_id,
      'ticket_type_id' => $activityInventory->ticket_type_id,
      'activity_start_date_time' => $activityInventory->activity_start_date_time,
      'activity_end_start_date_time' => $activityInventory->activity_end_start_date_time,
      'fit_selectable' => $activityInventory->fit_selectable,
      'stock' => $activityInventory->stock,
      'purchase_price' => $activityInventory->purchase_price,
      'sales_price' => $activityInventory->sales_price,
      'currency' => $activityInventory->currency,
      'notes' => $activityInventory->notes,
    ])
@endsection
