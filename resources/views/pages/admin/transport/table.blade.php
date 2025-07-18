@extends('layout.master')

@section('title', 'All Transports')

@section('content')
    @can('create', \App\Models\Transport\Transport::class)
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('transports.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
        <a class="btn btn-warning float-end" href="{{ route('transports.all', ['archived' => !$archived]) }}">
            {{ Icon::archive() }}
            <span>{{ $archived ? "Hide" : "Show" }} Archived</span>
        </a>
    </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <livewire:admin.transport.table :archived="$archived" />
    </x-admin.section.card>
@endsection
