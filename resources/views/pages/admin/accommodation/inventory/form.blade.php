@php
    /**
     * @var \App\Models\Accommodation\Accommodation $accommodation
     * @var \App\Models\Accommodation\AccommodationInventory|null $inventory
     */
    $inventory = $inventory ?? null;
    $title = __('accommodation.inventory.form.title.' . ($inventory === null ? 'create' : 'update')) . ' - ' . $accommodation->name;
@endphp

@extends('layout.master')

@section('title', $title)

@section('content')
    <livewire:admin.accommodation.inventory.form :accommodation="$accommodation" :inventory="$inventory" />
@endsection
