@extends('layout.component')
@section('title', 'View Flight')
@section('info')
    @include('partials.components.flights.info')
@endsection
@section('inventory')
    @include('partials.components.flights.table')
@endsection
