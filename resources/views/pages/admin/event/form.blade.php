@php
    /**
     * @var \App\Models\Tour\Event|null $event
     */
    $event = $event ?? null;
    $title = __('event.form.title.' . ($event === null ? 'create' : 'update'));
@endphp

@extends('layout.master')

@section('title', $title)

@section('content')
    <livewire:admin.event.form :event="$event" />
@endsection
