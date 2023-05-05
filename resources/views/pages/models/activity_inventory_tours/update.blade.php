@extends('layout.form', ['action' => route('activity-inventory-tours.update', ['tour' => $tour, 'activityInventoryTour' => $activityInventoryTour, ]),])

@section('title', 'Update Activity Inventory Tour')

@section('form-body')
    @include('partials.models.activity_inventory_tours.form', ['activityInventoryTour' => $activityInventoryTour, ])
@endsection
