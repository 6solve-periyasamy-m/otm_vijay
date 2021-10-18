@extends('layout.main')

@section('title', 'Create Address')

@section('content')
    @include('partials.models.addresses.form', ['action' => route('addresses.store'),])
@endsection
