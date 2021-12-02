@extends('layout.form', ['action' => route('users.create'), 'multipart' => true,])

@section('title', 'Create New User')

@section('form-body')
    @include('partials.users.form')
@endsection
