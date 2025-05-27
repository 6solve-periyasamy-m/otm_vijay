@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.master')

@section('title', 'Create Basic Quote for ' . $tour->name)

@section('content')
    <livewire:admin.quote.basic.form :tour="$tour" />
@endsection
