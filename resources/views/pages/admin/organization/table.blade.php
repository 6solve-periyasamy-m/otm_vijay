@extends('layout.master')

@php
    /**
     * @var \App\Models\Customer\Organization[] $organizations
     */
@endphp

@section('title', 'All Organizations')

@section('content')
    @can('create', \App\Models\Quote\Quote::class)
        <x-admin.section.card>
            <button class="btn btn-primary float-end" onclick="openModal('admin.organization.form')">
                {{ Icon::create() }}
                <span>Create New</span>
            </button>
        </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <livewire:admin.organization.table />
    </x-admin.section.card>
@endsection
