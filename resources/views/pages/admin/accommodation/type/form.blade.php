@php
    /**
     * @var \App\Models\Accommodation\AccommodationType|null $accommodationType
     */
    $accommodationType = $accommodationType ?? null;
    $title = __('accommodation.accommodation-type.title.' . ($accommodationType === null ? 'create' : 'update'));
    $route = $accommodationType === null ?
        route('accommodation-types.store') :
        route('accommodation-types.update', ['accommodationType' => $accommodationType,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $accommodationType?->name,])
    @include('partials.fields.submit')
@endsection