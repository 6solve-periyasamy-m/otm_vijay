@extends('layout.master')

@section('title', 'View Users')

@section('content')
    <x-admin.section.card>
        @if(\App\Repository\Authentication\UserRepository::getRemainingUserCount() > 0)
            <a class="btn btn-primary float-end" href="{{ route('users.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
        @else
            <span class="btn btn-dark float-end" href="{{ route('users.create') }}">
                    {{ Icon::create() }}
                    <span>User limit reached</span>
                </span>
        @endif
    </x-admin.section.card>
    <x-admin.section.card>
        <table id="users" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Verified</th>
                <th scope="col">Roles</th>
                <th scope="col">Created</th>
                <th scope="col">Active</th>
                @can('update', \App\Models\User::class)
                    <th scope="col">Actions</th>
                @endcan
            </tr>
            </thead>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->name }}</th>
                    <td>{{ $user->email }}</td>
                    <td>{{ f_datetime($user->email_verified_at) }}</td>
                    <td>{{ $user->roles->implode('title', ', ') }}</td>
                    <td>{{ f_datetime($user->created_at) }}</td>
                    <td>{{ f_bool(!$user->trashed()) }}</td>
                    @can('update', \App\Models\User::class)
                        @if(Auth::user()->getHighestRoleLevel() > $user->getHighestRoleLevel() || Auth::user()->id == $user->id)
                            <td>
                                @if($user->trashed())
                                    <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                        {{ Icon::edit() }}
                                    </span>
                                @else
                                    <a href="{{route('users.edit', ['user' => $user,])}}"
                                       class="btn btn-outline-success btn-sm mb-1" title="Edit">
                                        {{ Icon::edit() }}
                                    </a>
                                @endif
                                @can('delete', \App\Models\User::class)
                                    @if(Auth::user()->id == $user->id)
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                            {{ Icon::delete() }}
                                        </span>
                                    @else
                                        @if($user->trashed())
                                            @if(\App\Repository\Authentication\UserRepository::getRemainingUserCount() <= 0)
                                                <span class="btn btn-outline-dark btn-sm mb-1" title="Restore">
                                                    {{ Icon::enable() }}
                                                </span>
                                            @else
                                                <a href="#" class="btn btn-outline-warning btn-sm mb-1" title="Restore"
                                                   onclick="event.preventDefault();document.getElementById('user-{{ $user->id }}-restore').submit();">
                                                    {{ Icon::enable() }}
                                                </a>
                                                <form id="user-{{ $user->id }}-restore"
                                                      action="{{ route('users.restore', ['user' => $user->id,]) }}"
                                                      method="POST"
                                                      style="display: none;">{{ csrf_field() }}</form>
                                            @endif
                                        @else
                                            <a href="#" class="btn btn-outline-danger btn-sm mb-1" title="Delete"
                                               onclick="event.preventDefault();document.getElementById('user-{{ $user->id }}-delete').submit();">
                                                {{ Icon::delete() }}
                                            </a>
                                            <form id="user-{{ $user->id }}-delete"
                                                  action="{{ route('users.delete', ['user' => $user,]) }}"
                                                  method="POST"
                                                  style="display: none;">{{ csrf_field() }}</form>
                                        @endif
                                    @endif
                                @endcan
                            </td>
                        @else
                            <td>
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                        {{ Icon::edit() }}
                                    </span>
                                @can('delete', \App\Models\User::class)
                                    <span class="btn btn-outline-dark btn-sm mb-1">
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
