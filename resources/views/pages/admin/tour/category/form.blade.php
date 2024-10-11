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
$types = \App\View\Helper\DisplayModeType::toArray();
$colors = \App\View\Helper\DisplayModeColor::toArray();
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    <x-livewire.input width="4" name="name" value="{{ $category?->name }}" label="Name" required />
    <x-livewire.input.dropdown name="display_mode_type" label="Display Type" :items="$types" value="{{ $category?->display_mode_type?->value ?? 0 }}" width="4" />
    <x-livewire.input.dropdown name="display_mode_color" label="Color" :items="$colors" value="{{ $category?->display_mode_color?->value ?? 'dark' }}" width="4" />
    @include('partials.fields.submit')
@endsection
