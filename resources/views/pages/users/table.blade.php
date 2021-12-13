@extends('layout.master')

@section('title', 'View Users')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#users').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <a class="btn btn-primary float-end" href="{{ route('users.create') }}">
                <i class="icon-plus"></i>
                <span>Create New</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="users" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Verified</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Created</th>
                        @can('update', \App\Models\User::class)
                            <th scope="col">Actions</th>
                        @endcan
                    </tr>
                </thead>
                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $user->name }}</th>
                        <td>{{ $user->email }}</td>
                        <td>{{ StringFormatter::formatDateTime($user->email_verified_at) }}</td>
                        <td>{{ $user->roles->implode('title', ', ') }}</td>
                        <td>{{ StringFormatter::formatDateTime($user->created_at) }}</td>
                        @can('update', \App\Models\User::class)
                            @if(Auth::user()->getHighestRoleLevel() > $user->getHighestRoleLevel())
                            <td>
                                <a href="{{route('users.edit', ['user' => $user,])}}" class="btn btn-outline-success btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </a>
                                @can('delete', \App\Models\User::class)
                                <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                                   onclick="event.preventDefault();document.getElementById('user-{{ $user->id }}-delete').submit();">
                                    <i class="icon-trash"></i>
                                </a>
                                <form id="user-{{ $user->id }}-delete"
                                      action="{{ route('users.delete', ['user' => $user,]) }}" method="POST"
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
