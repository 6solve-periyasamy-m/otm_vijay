@extends('layout.component')
@section('title', 'View Activity')
@section('info')
    @include('partials.components.activity.info')
@endsection
@section('inventory')
    @include('partials.components.activity.table')
@endsection
