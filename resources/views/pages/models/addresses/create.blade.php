@extends('layout.main')

@section('title', 'Create Addresses')

@section('content')
    @include('partials.models.addresses.form', ['action' => route('addresses.store'),])
@endsection
