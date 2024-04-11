@php
/** @var \App\Models\Transport\Transport[] $transports */
@endphp

@extends('layout.master')

@section('title', 'All Transports')

@section('content')
    @can('create', \App\Models\Transport\Transport::class)
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('transports.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
    </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <table id="transport" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Transport Type</th>
                <th scope="col">Operator</th>
                <th scope="col">Departure Location</th>
                <th scope="col">Arrival Location</th>
                <th scope="col">Description</th>
                <th scope="col">Currency</th>
                <th scope="col">Is Domestic</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($transports as $transport)
                <tr>
                    <td><a href="{{ route('transports.view', ['transport' => $transport,]) }}">{{ $transport->name }}</a></td>
                    <td>{{ $transport->transportType->name }}</td>
                    <td>{{ $transport->operator->name }}</td>
                    <td>{{ $transport->departureAddress->name }}</td>
                    <td>{{ $transport->arrivalAddress->name }}</td>
                    <td>{{ $transport->description }}</td>
                    <td>{{ $transport->currency }}</td>
                    <td>{{ $transport->is_domestic ? "Yes" : "No" }}</td>
                    <td>{{ $transport->internal_notes }}</td>
                    <td class="actions-3">
                        @can('create', \App\Models\Transport\Transport::class)
                            <a href="{{route('transports.return', ['transport' => $transport,])}}" title="Return Trip" class="btn btn-outline-blue btn-sm mb-1">
                                {{ Icon::returnTrip() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::returnTrip() }}
                                </span>
                        @endcan
                        @can('update', \App\Models\Transport\Transport::class)
                            <a href="{{route('transports.edit', ['transport' => $transport,])}}" title="Edit" class="btn btn-sm btn-outline-success mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                        @endcan
                        @can('delete', \App\Models\Transport\Transport::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1" title="Delete"
                               onclick="event.preventDefault();document.getElementById('transport-{{ $transport->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="transport-{{ $transport->id }}-delete"
                                  action="{{ route('transports.delete', ['transport' => $transport,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
