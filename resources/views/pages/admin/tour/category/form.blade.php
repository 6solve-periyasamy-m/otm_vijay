@php
/**
 * @var \App\Models\Tour\TourCategory|null $category
 */
$category = $category ?? null;
$route = $category !== null ?
            route('tour-categories.update', ['category' => $category,]) :
            route('tour-categories.create');
$title = $category !== null ?
            "Update Tour Category" :
            "Create Tour Category";
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    <x-livewire.input name="name" value="{{ $category?->name }}" label="Name" required />
    @include('partials.fields.submit')
@endsection
