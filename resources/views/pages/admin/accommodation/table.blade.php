@extends('layout.master')

@section('title', 'View Accommodation')

@section('content')
    @can('create', \App\Models\Accommodation\AccommodationInventory::class)
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('accommodations.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
    </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <table style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Audit Date</th>
                <th scope="col">Address</th>
                <th scope="col">Currency</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($accommodations as $accommodation)
                <tr>
                    <td><a href="{{ route('accommodations.view', ['accommodation' => $accommodation,]) }}">{{ $accommodation->name }}</a></td>
                    <td>{{ $accommodation->description }}</td>
                    <td>{{ f_date($accommodation->audit_date) }}</td>
                    <td>{{ $accommodation->address }}</td>
                    <td>{{ $accommodation->currency }}</td>
                    <td class="actions">
                        @can('update', \App\Models\Accommodation\Accommodation::class)
                            <a href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                        @endcan
                        @can('delete', \App\Models\Accommodation\Accommodation::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1" title="Delete"
                               onclick="event.preventDefault();document.getElementById('accommodation-{{ $accommodation->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="accommodation-{{ $accommodation->id }}-delete"
                                  action="{{ route('accommodations.delete', ['accommodation' => $accommodation,]) }}" method="POST"
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
