@extends('layout.master')

@php
    /**
     * @var \App\Models\Customer\Agent[] $agents
     */
@endphp

@section('title', 'All Agents')

@section('content')
    @can('create', \App\Models\Quote\Quote::class)
        <x-admin.section.card>
            <button class="btn btn-primary float-end" onclick="openModal('admin.agent.form')">
                {{ Icon::create() }}
                <span>Create New</span>
            </button>
        </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <livewire:admin.agent.table />
    </x-admin.section.card>
@endsection
