@extends('layout.form', ['action' => route('airlines.store'),])

@section('title', 'Create Airline')

@section('form-body')
    @include('partials.models.airlines.form')
@endsection
