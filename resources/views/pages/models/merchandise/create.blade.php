@extends('layout.form', ['action' => route('merchandise.store', ['tour' => $tour, ]),])

@section('title', 'Create Merchandise')

@section('form-body')
    @include('partials.models.merchandise.form')
@endsection
