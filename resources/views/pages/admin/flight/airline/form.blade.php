@php
    /**
     * @var \App\Models\Flight\Airline|null $airline
     */
    $airline = $airline ?? null;
    $title = __('flight.airline.form.title.' . ($airline === null ? 'create' : 'update'));
    $route = $airline === null ?
        route('airlines.store') :
        route('airlines.update', ['airline' => $airline,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $airline?->name ?? null,])
    @include('partials.fields.submit')
@endsection