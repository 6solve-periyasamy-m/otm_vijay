@extends('layout.form', ['action' => route('addresses.store'),])

@section('title', 'Create Address')

@section('form-body')
    @include('partials.models.addresses.form')
@endsection
