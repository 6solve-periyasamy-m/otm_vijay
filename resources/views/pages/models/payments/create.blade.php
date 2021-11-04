@extends('layout.master')

@section('title', 'Create Payment')

@section('content')
    @include('partials.models.payments.form', ['action' => route('payments.store', ['order' => $order, ]),])
@endsection
