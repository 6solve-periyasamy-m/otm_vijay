@extends('layout.component')
@section('title', 'View transport')
@section('info')
    @include('partials.components.transport.info')
@endsection
@section('inventory')
    @include('partials.components.transport.table')
@endsection
