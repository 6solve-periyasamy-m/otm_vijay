@php
    /**
     * @var \App\Models\Transport\TransportType|null $transportType
     */
    $transportType = $transportType ?? null;
    $title = __('transport.transport-type.form.title.' . ($transportType === null ? 'create' : 'update'));
    $route = $transportType === null ?
        route('transport-types.store') :
        route('transport-types.update', ['transportType' => $transportType,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $transportType?->name ?? null,])
    @include('partials.fields.submit')
@endsection