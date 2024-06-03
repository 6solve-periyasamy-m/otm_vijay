@php
    /**
     * @var \App\Models\Tour\Tour|null $tour
     */
    $tour = $tour ?? null;
    $title = __('tours.form.title.' . ($tour === null ? 'create' : 'update'));
@endphp

@extends('layout.master')

@section('title', $title)

@section('content')
    <livewire:admin.tour.form :tour="$tour" />
@endsection
