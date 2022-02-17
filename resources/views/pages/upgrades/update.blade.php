@extends('layout.form', ['action' => $action,])

@section('title', 'Update ' . $name . ' Upgrade')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $upgrade->description ?? null, 'width' => 6,])
    @include('partials.fields.text', ['name' => 'Upgrade Price', 'field' => 'sales_price', 'value' => $inventoryTour->tour_sales_price ?? null, 'width' => 6,])
    @include('partials.fields.submit')
@endsection
