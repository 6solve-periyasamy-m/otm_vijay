@extends('layout.master')

@section('title', 'All Activities')

@section('content')
@can('create', \App\Models\Activity\Activity::class)
<x-admin.section.card>
    <a class="btn btn-primary float-end" href="{{ route('activities.create') }}">
        {{ Icon::create() }}
        <span>Create New</span>
    </a>
    <a class="btn btn-warning float-end" href="{{ route('activities.all', ['archived' => !$archived]) }}">
        {{ Icon::archive() }}
        <span>{{ $archived ? "Hide" : "Show" }} Archived</span>
    </a>
</x-admin.section.card>
@endcan
<x-admin.section.card>
    <livewire:admin.activity.table :archived="$archived"/>
</x-admin.section.card>
@endsection
