@php
    $viewable = auth()->user()->can('read', \App\Models\User::class);
@endphp

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
                    <td class="actions">
                        <x-admin.table.button class="btn-outline-blue" href="{{ route('users.profile', ['user' => $user->id,]) }}" active="{{ $viewable ? 1 : 0 }}">
                            {{ Icon::view() }}
                        </x-admin.table.button>
                        @if($user->trashed())
                            <x-admin.table.button.form class="btn-outline-warning" href="{{ route('users.restore', ['user' => $user->id,]) }}" active="{{ $user->isRecoverable(auth()->user()) ? 1 : 0 }}">
                                {{ Icon::enable() }}
                            </x-admin.table.button.form>
                        @else
                            <x-admin.table.button.form class="btn-outline-warning" href="{{ route('users.delete', ['user' => $user->id,]) }}" active="{{ $user->isDeletable(auth()->user()) ? 1 : 0 }}">
                                {{ Icon::delete() }}
                            </x-admin.table.button.form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
