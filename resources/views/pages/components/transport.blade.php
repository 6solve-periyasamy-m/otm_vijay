@extends('layout.component')
@section('title', 'View Transports')
@section('info')
    @include('partials.components.transport.info')
@endsection
@section('inventory')
    @include('partials.components.transport.table')
@endsection
