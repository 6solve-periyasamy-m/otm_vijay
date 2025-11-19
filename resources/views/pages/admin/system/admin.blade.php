@extends('layout.master')

@section('title', 'System Configuration')

@section('content')
    @can('read', \App\Models\User::class)
        @include('partials.admin.system.users')
        @include('partials.admin.system.roles')
    @endcan
    @include('partials.admin.supplier.table')
@endsection