@extends('layout.main')

@section('title', 'Create Airlines')

@section('content')
    @include('partials.models.airlines.form', ['action' => route('airlines.store'),])
@endsection
