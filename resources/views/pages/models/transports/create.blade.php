@extends('layout.main')

@section('title', 'Create Transports')

@section('content')
    @include('partials.models.transports.form', ['action' => route('transports.store'),])
@endsection
