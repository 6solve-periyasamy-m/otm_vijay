@php
    /**
     * @var \App\Models\Transport\TransportOccupancy|null $transportOccupancy
     */
    $isEdit = isset($transportOccupancy);
    $title = __('transport.occupancy.form.title.' . ($isEdit ? 'update' : 'create'));

    $route = $isEdit
        ? route('occupancy.update', ['occupancy' => $transportOccupancy])
        : route('occupancy.store');
@endphp

@extends('layout.form', ['action' => $route])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $transportOccupancy?->name ?? null,'width'=>10,])
    @include('partials.fields.text', ['name' => 'Maximum Occupancy', 'field' => 'maximum_occupancy', 'value' => $transportOccupancy?->maximum_occupancy ?? null,'width'=>2,])
    @include('partials.fields.submit')
@endsection