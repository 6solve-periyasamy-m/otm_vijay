@extends('layout.master')

@section('title', 'Create Activity Inventory')

@section('content')
    @include('partials.models.activity_inventories.form', ['action' => route('activity-inventories.store', ['activity' => $activity, ]),])
@endsection
