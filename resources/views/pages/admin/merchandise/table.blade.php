@php
    /**
     * @var \App\Models\Merchandise\Merchandise[] $merchandise
     */
@endphp

@extends('layout.master')

@section('title', 'All Merchandise')

@push('footer-stack')
    <style>
        .merch-thumbnail-container {
            width: 100px;
            height: 100px;
            padding: 0;
        }
        .merch-thumbnail {
            width: 100px;
            height: 100px;
            background-image: url('{{ asset(setting('company.logo')) }}');
            background-size: 100px 100px;
            background-repeat: no-repeat;
        }
    </style>
@endpush

@section('content')
    @can('create', \App\Models\Merchandise\Merchandise::class)
        <x-admin.section.card>
            <a class="btn btn-primary float-end" href="{{ route('merchandise.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
        </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <table id="merchandise" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Variants</th>
                <th scope="col">Orders</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($merchandise as $merch)
                <tbody>
                <tr>
                    <td><div class="merch-thumbnail-container"><img class="merch-thumbnail" src="{{ $merch->asset }}"></div></td>
                    <td><a href="{{ route('merchandise.view', ['merchandise' => $merch,]) }}">{{ $merch->name }}</a></td>
                    <td>{{ $merch->inventory()->count() }}</td>
                    <td>{{ $merch->repository->getOrderCount() }}</td>
                    <td>{{ $merch->internal_notes }}</td>
                    <td class="actions">
                        @can('update', \App\Models\Merchandise\Merchandise::class)
                            <a href="{{route('merchandise.edit', ['merchandise' => $merch,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </span>
                        @endcan
                        @can('delete', \App\Models\Merchandise\Merchandise::class)
                            <a href="javascript:$('#merchandise-{{ $merch->id }}-delete').submit()" title="Delete" class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::delete() }}
                            </a>
                            <form id="merchandise-{{ $merch->id }}-delete"
                                  action="{{ route('merchandise.delete', ['merchandise' => $merch,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
                </tbody>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
