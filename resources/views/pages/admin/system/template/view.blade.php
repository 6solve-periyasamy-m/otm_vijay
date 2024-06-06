@extends('layout.master')

@section('title', 'Default Text Templates')

@section('content')
    <x-admin.section.card>
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('settings.edit') }}" class="btn btn-warning">{{ Icon::back() }} Back to Settings</a>
            </div>
            <div>
                <a class="btn btn-primary" href="{{ route('settings.template.form') }}">
                    {{ Icon::create() }} Create New
                </a>
            </div>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="float-end">
        </div>
    </x-admin.section.card>
    <livewire:admin.system.large-text-template.table />
@endsection