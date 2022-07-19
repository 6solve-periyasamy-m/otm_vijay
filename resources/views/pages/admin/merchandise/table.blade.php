@php
    /**
     * @var \App\Models\Merchandise\Merchandise[] $merchandise
     */
@endphp

@extends('layout.master')

@section('title', 'All Merchandise')

@push('header-stack')
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

@push('footer-ready', "$('#merchandise').DataTable({fixedHeader: true,});")

@section('content')
    @can('create', \App\Models\Merchandise\Merchandise::class)
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary float-end" href="{{ route('merchandise.create') }}">
                    <i class="icon-plus"></i>
                    <span>Create New</span>
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <table id="merchandise" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Variants</th>
                    <th scope="col">Orders</th>
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
                        <td class="actions">
                            @can('update', \App\Models\Merchandise\Merchandise::class)
                                <a href="{{route('merchandise.edit', ['merchandise' => $merch,])}}" class="btn btn-outline-success btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                            @endcan
                            @can('delete', \App\Models\Merchandise\Merchandise::class)
                                <a href="javascript:$('#merchandise-{{ $merch->id }}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
                                    <i class="icon-trash"></i>
                                </a>
                                <form id="merchandise-{{ $merch->id }}-delete"
                                      action="{{ route('merchandise.delete', ['merchandise' => $merch,]) }}" method="POST"
                                      style="display: none;">{{ csrf_field() }}</form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-trash"></i>
                                </span>
                            @endcan
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@endsection
