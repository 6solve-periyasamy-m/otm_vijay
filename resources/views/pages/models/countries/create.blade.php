@extends('layout.master')

@section('title', 'Create Country')

@section('content')
    @include('partials.models.countries.form', ['action' => route('countries.store'),])
@endsection
