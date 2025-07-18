@extends('layout.master')

@section('title', 'View Accommodation')

@section('content')
    @can('create', \App\Models\Accommodation\AccommodationInventory::class)
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('accommodations.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
        <a class="btn btn-warning float-end" href="{{ route('accommodations.all', ['archived' => !$archived]) }}">
            {{ Icon::archive() }}
            <span>{{ $archived ? "Hide" : "Show" }} Archived</span>
        </a>
    </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <livewire:admin.accommodation.table :archived="$archived" />
    </x-admin.section.card>
@endsection
