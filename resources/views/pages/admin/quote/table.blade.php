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
        <table style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">{{ __('quotes.table.reference') }}</th>
                <th scope="col">{{ __('quotes.table.package') }}</th>
                <th scope="col">{{ __('quotes.table.description') }}</th>
                <th scope="col">{{ __('quotes.table.lead') }}</th>
                <th scope="col">{{ __('quotes.table.email') }}</th>
                <th scope="col">{{ __('quotes.table.expiry') }}</th>
                <th scope="col">{{ __('quotes.table.status') }}</th>
                <th scope="col">{{ __('custom.table.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quotes as $quote)
                <tr>
                    <td><a href="{{ route('quotes.view', ['quote' => $quote,]) }}">{{ $quote->ref }}</a></td>
                    <td>{{ $quote->name }}</td>
                    <td>{{ $quote->description }}</td>
                    <td>{{ $quote->leadTraveller?->name ?? 'Lead Traveller Not Set' }}</td>
                    <td>{{ $quote->leadTraveller?->email ?? 'Lead Traveller Not Set' }}</td>
                    <td>{{ f_date($quote->expires)}}</td>
                    <td>{{ $quote->status->badge() }}</td>
                    <td class="actions">
                        @can('update', \App\Models\Quote\Quote::class)
                            <a href="{{route('quotes.edit', ['quote' => $quote,])}}" title="Edit" class="btn btn-sm btn-outline-success mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                        @endcan
                        @can('delete', \App\Models\Quote\Quote::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1" title="Delete"
                               onclick="event.preventDefault();document.getElementById('quote-{{ $quote->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="quote-{{ $quote->id }}-delete" action="{{ route('quotes.delete', ['quote' => $quote,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-admin.section.card>
@endsection
