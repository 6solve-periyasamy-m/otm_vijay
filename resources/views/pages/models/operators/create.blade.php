@extends('layout.master')

@section('title', 'Create Operator')

@section('content')
    @include('partials.models.operators.form', ['action' => route('operators.store'),])
@endsection
