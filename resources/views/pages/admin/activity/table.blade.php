@extends('layout.master')

@section('title', 'All Activities')

@section('content')
@can('create', \App\Models\Activity\Activity::class)
<x-admin.section.card>
    <a class="btn btn-primary float-end" href="{{ route('activities.create') }}">
        {{ Icon::create() }}
        <span>Create New</span>
    </a>
</x-admin.section.card>
@endcan
<x-admin.section.card>
    <livewire:admin.activity.table />
</x-admin.section.card>
@endsection
