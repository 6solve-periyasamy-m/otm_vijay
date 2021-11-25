@extends('layout.form', ['action' => route('roles.update', ['role' => $role,]),])

@section('title', 'Edit User')

@section('form-body')
    @include('partials.users.form', [
                    'name' => $role->name,
                    'title' => $role->title,
                    'level' => $role->level,
                ])
@endsection

@push('footer-stack')
    @include('partials.scripts.multicheck')
@endpush
