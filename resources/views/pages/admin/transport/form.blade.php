@php
    /**
     * @var \App\Models\Transport\Transport|null $transport
     */
    $transport = $transport ?? null;
    $title = __('transport.form.title.' . ($transport === null ? 'create' : 'update'));
    $route = $transport === null ?
        route('transports.store') :
        route('transports.update', ['transport' => $transport,]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    @can('create', \App\Models\Transport\TransportType::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport?->transport_type_id,
                     'route' => 'transport-types', 'createRoute' => route('transport-types.create'), 'width' => 6,])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport?->transport_type_id,
                 'route' => 'transport-types', 'width' => 6,])
    @endcan
    @can('create', \App\Models\Transport\Operator::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Operator', 'field' => 'operator_id', 'value' => $transport?->operator_id,
                     'route' => 'operators', 'createRoute' => route('operators.create'), 'width' => 6,])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Operator', 'field' => 'operator_id', 'value' => $transport?->operator_id,
                 'route' => 'operators', 'width' => 6,])
    @endcan
    <hr class="splitter">
    @include('partials.fields.checkbox', ['name' => 'Show Customer Addresses', 'field' => 'customers', 'width' => 6,])
    @include('partials.fields.checkbox', ['name' => 'Is Domestic', 'field' => 'is_domestic', 'value' => $transport?->is_domestic, 'width' => 6,])
    @include('partials.fields.prefab.addresses.transport', ['namePrefix' => 'Departure', 'prefix' => 'departure_', 'value' => $transport?->departure_address_id, 'customerCheckbox' => true, 'width' => 6,])
    @include('partials.fields.prefab.addresses.transport', ['namePrefix' => 'Arrival', 'prefix' => 'arrival_', 'value' => $transport?->arrival_address_id, 'customerCheckbox' => true, 'width' => 6,])
    <hr class="splitter">
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $transport?->name,'width'=>10,])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2, 'value' => $transport?->image_url,])
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $transport?->description,])
    <x-livewire.input.select.currency name="currency_id" label="Currency" value="{{$transport?->currency_id}}" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $transport?->internal_notes])
    @include('partials.fields.submit')
@endsection