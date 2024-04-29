@extends('layout.master')

@section('title', 'View Roles')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#roles').DataTable({fixedHeader: true, order:[[0,'desc']]});
        });
    </script>
@endsection

@section('content')
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('roles.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
    </x-admin.section.card>
    <x-admin.section.card>
        <table id="roles" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Level</th>
                <th scope="col">Name</th>
                @can('update', \App\Models\User::class)
                    <th scope="col">Actions</th>
                @endcan
            </tr>
            </thead>
            @foreach($roles as $role)
                <tr>
                    <th scope="row">{{ $role->level }}</th>
                    <td>{{ $role->title }}</td>
                    @can('update', \App\Models\User::class)
                        @if(Auth::user()->getHighestRoleLevel() > $role->level)
                            <td>
                                <a href="{{route('roles.edit', ['role' => $role,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                @can('delete', \App\Models\User::class)
                                    <a href="#" class="btn btn-outline-danger btn-sm mb-1" title="Delete"
                                       onclick="event.preventDefault();document.getElementById('role-{{ $role->id }}-delete').submit();">
                                        {{ Icon::delete() }}
                                    </a>
                                    <form id="user-{{ $role->id }}-delete"
                                          action="{{ route('roles.delete', ['role' => $role,]) }}" method="POST"
                                          style="display: none;">{{ csrf_field() }}</form
                                @endcan
                            </td>
                        @else
                            <td>
                                    <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                        {{ Icon::edit() }}
                                    </span>
                                @can('delete', \App\Models\User::class)
                                    <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                            {{ Icon::delete() }}
                                        </span>
                                @endcan
                            </td>
                        @endif
                    @endcan
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
