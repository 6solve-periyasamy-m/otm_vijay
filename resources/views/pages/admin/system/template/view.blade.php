@extends('layout.master')

@section('title', 'Default Text Templates')

@section('content')
    <x-admin.section.card>
        <div class="float-end">
            <a class="btn btn-primary" href="{{ route('settings.template.form') }}">
                {{ Icon::create() }} Create New
            </a>
        </div>
    </x-admin.section.card>
    <livewire:admin.system.large-text-template.table />
@endsection