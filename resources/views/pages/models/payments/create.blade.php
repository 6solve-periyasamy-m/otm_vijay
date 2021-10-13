@extends('layout.main')

@section('title', 'Create Payments')

@section('content')
    @include('partials.models.payments.form', ['action' => route('payments.store'),])
@endsection
