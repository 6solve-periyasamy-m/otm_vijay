@extends('layout.master')

@section('title', 'System Configuration')

@section('content')
    @can('update', \App\Models\System\Setting::class)
        @include('partials.admin.system.settings')
    @endcan
    @can('read', \App\Models\User::class)
        @include('partials.admin.system.users')
        @include('partials.admin.system.roles')
    @endcan
    @include('partials.admin.supplier.table')
@endsection