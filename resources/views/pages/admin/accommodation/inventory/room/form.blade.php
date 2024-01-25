@php
    /**
     * @var \App\Models\Accommodation\RoomType|null $roomType
     */
    $roomType = $roomType ?? null;
    $title = __('accommodation.inventory.room-type.form.title.' . ($roomType === null ? 'create' : 'update'));
    $route = $roomType === null ?
        route('room-types.store') :
        route('room-types.update', ['roomType' => $roomType,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $roomType?->name ?? null,'width'=>10,])
    @include('partials.fields.text', ['name' => 'Maximum Occupancy', 'field' => 'maximum_occupancy', 'value' => $roomType?->maximum_occupancy ?? null,'width'=>2,])
    @include('partials.fields.submit')
@endsection