@php
    /**
     * @var \App\Models\Flight\Airport|null $airport
     */
    $airport = $airport ?? null;
    $title = __('flight.airport.form.title.' . ($airport === null ? 'create' : 'update'));
    $route = $airport === null ?
        route('airports.store') :
        route('airports.update', ['airport' => $airport,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $airport?->name ?? null,])
    @include('partials.fields.text', ['name' => 'IATA Code', 'field' => 'iata_code', 'value' => $airport?->iata_code ?? null,])
    @include('partials.fields.prefab.addresses.switcher', ['address' => $airport?->address])
    @include('partials.fields.submit')
@endsection