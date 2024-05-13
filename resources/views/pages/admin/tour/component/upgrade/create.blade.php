@extends('layout.form', ['action' => $action,])

@section('title', 'Create ' . $name . ' Upgrade')

@section('form-body')
    @include('partials.fields.selector.default',
            ['name' => $name, 'field' => 'inventory_id', 'fullRoute' => route('api.inventory.' . $model . '.upgrade.select', ['inventoryTour' => $inventoryTour,]),'preselect'=>false,])
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null, 'width' => 6,])
    @include('partials.fields.text', ['name' => 'Upgrade Price', 'field' => 'sales_price', 'value' => $sales_price ?? null, 'width' => 6,])
    @include('partials.fields.submit')
@endsection
