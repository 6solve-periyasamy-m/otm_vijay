@php
    /**
     * @var \App\Models\Tour\Event|null $event
     */
    $event = $event ?? null;
    $title = __('event.form.title.' . ($event === null ? 'create' : 'update'));
    $route = $event === null ?
        route('events.store') :
        route('events.update', ['event' => $event,]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $event?->name ?? null,])
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $event?->description ?? null,])
    @include('partials.fields.date',
                ['name' => 'Start Date', 'field' => 'starts_at', 'value' => $event?->starts_at ?? null,
                 'onChange' => 'changeDate($(\'#starts_at-input\'), $(\'#ends_at-input\'))', 'width' => 6,])
    @include('partials.fields.date',
                ['name' => 'End Date', 'field' => 'ends_at', 'value' => $event?->ends_at ?? null,
                 'onChange' => 'removeAutoset($(\'#starts_at-input\'), $(\'#ends_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
    @include('partials.fields.text', ['name' => 'Booking URL', 'field' => 'booking_url', 'value' => $event?->booking_url ?? null,])
    @include('partials.fields.textarea', ['name' => 'Notes', 'field' => 'notes', 'value' => $event?->notes ?? null])
    @include('partials.fields.submit')
@endsection