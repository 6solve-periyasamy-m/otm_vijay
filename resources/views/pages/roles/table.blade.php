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
    <div class="card">
        <div class="card-body">
            <a class="btn btn-primary float-end" href="{{ route('roles.create') }}">
                <i class="icon-plus"></i>
                <span>Create New</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
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
                                    <a href="{{route('roles.edit', ['role' => $role,])}}" class="btn btn-outline-success btn-sm mb-1">
                                        <i class="icon-note"></i>
                                    </a>
                                    @can('delete', \App\Models\User::class)
                                    <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                                       onclick="event.preventDefault();document.getElementById('role-{{ $role->id }}-delete').submit();">
                                        <i class="icon-trash"></i>
                                    </a>
                                    <form id="user-{{ $role->id }}-delete"
                                          action="{{ route('roles.delete', ['role' => $role,]) }}" method="POST"
                                          style="display: none;">{{ csrf_field() }}</form
                                    @endcan
                                </td>
                            @else
                                <td>
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                        <i class="icon-note"></i>
                                    </span>
                                    @can('delete', \App\Models\User::class)
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                            <i class="icon-trash"></i>
                                        </span>
                                    @endcan
                                </td>
                            @endif
                        @endcan
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
