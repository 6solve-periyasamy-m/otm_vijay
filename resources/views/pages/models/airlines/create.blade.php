@extends('layout.master')

@section('title', 'Create Airline')

@section('content')
    @include('partials.models.airlines.form', ['action' => route('airlines.store'),])
@endsection
