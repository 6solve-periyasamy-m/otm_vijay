@extends('layout.master')

@section('title', 'Create Tour')

@section('content')
    @include('partials.models.tours.form', ['action' => route('tours.store'),])
@endsection
