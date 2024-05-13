@php
    /**
     * @var \App\Models\Flight\Flight|null $flight
     */
    $flight = $flight ?? null;
    $title = __('flight.form.title.' . ($flight === null ? 'create' : 'update'));
    $route = $flight === null ?
        route('flights.store') :
        route('flights.update', ['flight' => $flight,]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    @can('create', \App\Models\Flight\Airline::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Airline', 'field' => 'airline_id', 'value' => $flight?->airline_id,
                     'route' => 'airlines', 'createRoute' => route('airlines.create'),])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Airline', 'field' => 'airline_id', 'value' => $flight?->airline_id,
                 'route' => 'airlines'])
    @endcan
    @can('create', \App\Models\Flight\Airport::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Departure Airport', 'field' => 'departure_airport_id', 'value' => $flight?->departure_airport_id,
                     'route' => 'airports', 'createRoute' => route('airports.create'),])
    @else
        @include('partials.fields.selector.default',
                    ['name' => 'Departure Airport', 'field' => 'departure_airport_id', 'value' => $flight?->departure_airport_id,
                     'route' => 'airports',])
    @endcan
    @can('create', \App\Models\Flight\Airport::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Arrival Airport', 'field' => 'arrival_airport_id', 'value' => $flight?->arrival_airport_id,
                     'route' => 'airports', 'createRoute' => route('airports.create'),])
    @else
        @include('partials.fields.selector.default',
                    ['name' => 'Arrival Airport', 'field' => 'arrival_airport_id', 'value' => $flight?->arrival_airport_id,
                     'route' => 'airports',])
    @endcan
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'value' => $flight?->image_url,])
    @include('partials.fields.checkbox', ['name' => 'Is Domestic', 'field' => 'is_domestic', 'value' => $flight?->is_domestic,])
    @include('partials.fields.date', ['name' => 'Available From', 'field' => 'available_from', 'value' => $flight?->available_from,])
    <x-livewire.input.select.currency name="currency_id" label="Currency" value="{{$flight?->currency_id}}" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $flight?->internal_notes])
    @include('partials.fields.submit')
@endsection
