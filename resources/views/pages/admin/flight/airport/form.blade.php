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
    @include('partials.fields.prefab.addresses.switcher', [
                        'location_type_id' => $airport?->address->location_type_id,
                        'address_line_1' => $airport?->address->address_line_1,
                        'address_line_2' => $airport?->address->address_line_2,
                        'town' => $airport?->address->town,
                        'region' => $airport?->address->region,
                        'country_id' => $airport?->address->country_id,
                        'postcode' => $airport?->address->postcode,
                    ])
    @include('partials.fields.submit')
@endsection