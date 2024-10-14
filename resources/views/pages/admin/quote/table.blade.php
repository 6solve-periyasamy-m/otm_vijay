@extends('layout.master')

@php
/**
 * @var \App\Models\Quote\Quote[] $quotes
 */
@endphp

@section('title', 'All Quotes')

@section('content')
    @can('create', \App\Models\Quote\Quote::class)
        <x-admin.section.card>
            <a class="btn btn-success float-end" href="{{ route('quotes.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px;" href="{{ route('quotes.all', ['historic' => !($historic ?? true),]) }}">
                <i class="icon-eye"></i>
                <span>{{ !($historic ?? true) ? "Show" : "Hide" }} Historic (Older than {{ setting('system.historic', 6) }} month(s))</span>
            </a>
        </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <livewire:admin.quote.table />
    </x-admin.section.card>
@endsection
