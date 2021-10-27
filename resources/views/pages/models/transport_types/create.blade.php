@extends('layout.master')

@section('title', 'Create Transport Type')

@section('content')
    @include('partials.models.transport_types.form', ['action' => route('transport-types.store'),])
@endsection
