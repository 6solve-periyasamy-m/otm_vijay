@extends('layout.master')

@section('title', 'All Flights')

@section('content')
@can('create', \App\Models\Flight\Flight::class)
<x-admin.section.card>
    <a class="btn btn-primary float-end" href="{{ route('flights.create') }}">
        {{ Icon::create() }}
        <span>Create New</span>
    </a>
    <a class="btn btn-warning float-end" href="{{ route('flights.all', ['archived' => !$archived]) }}">
        {{ Icon::archive() }}
        <span>{{ $archived ? "Hide" : "Show" }} Archived</span>
    </a>
</x-admin.section.card>
@endcan
<x-admin.section.card>
    <livewire:admin.flight.table :archived="$archived" />
</x-admin.section.card>
@endsection
