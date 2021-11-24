@extends('layout.form', ['action' => route('payments.store', ['order' => $order, ]),])

@section('title', 'Create Payment')

@section('form-body')
    @include('partials.models.payments.form')
@endsection
