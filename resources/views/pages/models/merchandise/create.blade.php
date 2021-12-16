@extends('layout.form', ['action' => route('merchandise.store', ['tour' => $tour, ]), 'multipart' => true,])

@section('title', 'Create Merchandise')

@section('form-body')
    @include('partials.models.merchandise.form')
@endsection
