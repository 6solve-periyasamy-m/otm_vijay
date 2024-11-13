@php /** @var \App\Models\Tour\Tour|null $tour */ $tour = $tour ?? null; $stock = $tour->stock ?? 0; @endphp

@extends('layout.master')

@section('title', "Accommodation Selector - {$tour->name} - Stock Required: {$stock}")

@section('content')
    <livewire:admin.tour.accommodation-selector :start="$tour?->date_from" :end="$tour?->date_to" :tour="$tour->id" :travellers="$stock" />
@endsection
