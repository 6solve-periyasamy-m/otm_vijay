@extends('layout.form', ['action' => route('users.update', ['user' => $user,]), 'multipart' => true,])

@section('title', 'Edit User')

@section('form-body')
    @include('partials.users.form', [
                    'name' => $user->name,
                    'email' => $user->email,
                ])
@endsection
