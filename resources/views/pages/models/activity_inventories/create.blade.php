@extends('layout.form', ['action' => route('activity-inventories.store', ['activity' => $activity, ]),])

@section('title', 'Create Activity Inventory')

@section('form-body')
    @include('partials.models.activity_inventories.form')
@endsection
