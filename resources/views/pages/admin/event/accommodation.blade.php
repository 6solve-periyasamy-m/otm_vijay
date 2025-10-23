@php /** @var \App\Models\Tour\Event|null $event */ $event = $event ?? null; @endphp

@extends('layout.master')

@section('title', "Accommodation by night report")

@section('content')
    <livewire:admin.event.accommodation-report-selector :event="$event" />
@endsection
