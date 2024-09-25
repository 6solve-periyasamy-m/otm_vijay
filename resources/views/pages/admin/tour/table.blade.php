@extends('layout.master')

@section('title', 'All Tours')

@section('content')
    <div class='card'>
        <div class="card-body">
            <a class="btn btn-success float-end" href="{{ route('tours.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px;" href="{{ route('tours.all', ['hideNoCategory' => !($hideNoCategory ?? true),]) }}">
                <i class="icon-eye"></i>
                <span>{{ !($hideNoCategory ?? true) ? "Hide" : "Show" }} Tours Without Category</span>
            </a>
        </div>
    </div>
    <x-admin.section.card>
        <livewire:admin.tour.table :hide-no-category="$hideNoCategory"/>
    </x-admin.section.card>
@endsection
