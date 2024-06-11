@extends('layout.master')

@section('title', 'View Accommodation')

@section('content')
    @can('create', \App\Models\Accommodation\AccommodationInventory::class)
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('accommodations.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
    </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <livewire:admin.accommodation.table />
    </x-admin.section.card>
@endsection
