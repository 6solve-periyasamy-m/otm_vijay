@extends('layout.component')
@section('title', 'View Accommodation')
@section('info')
    @include('partials.components.accommodation.info')
@endsection
@section('inventory')
    @include('partials.components.accommodation.table')
@endsection
