@extends('layout.master')

@php
    /**
     * @var \App\Models\Customer\Organization[] $organizations
     */
@endphp

@section('title', 'All Organizations')

@section('content')
    @can('create', \App\Models\Quote\Quote::class)
        <div class="card">
            <div class="card-body">
                <button class="btn btn-primary float-end" onclick="openModal('admin.organization.form')">
                    {{ Icon::create() }}
                    <span>Create New</span>
                </button>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <livewire:admin.organization.table />
        </div>
    </div>
@endsection
