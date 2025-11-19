@extends('layout.master')

@section('title', 'System Configuration')

@section('content')
    @include('partials.admin.system.attributes')
    @can('read', \App\Models\User::class)
        @include('partials.admin.system.users')
        @include('partials.admin.system.roles')
    @endcan
    @include('partials.admin.supplier.table')
@endsection