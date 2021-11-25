@extends('layout.form', ['action' => route('roles.create'),])

@section('title', 'Create New Role')

@section('form-body')
    @include('partials.users.permissions.form')
@endsection
