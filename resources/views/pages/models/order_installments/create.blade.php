@extends('layout.form', ['action' => route('order-installments.store', ['order' => $order, ]),])

@section('title', 'Create Order Installment')

@section('form-body')
    @include('partials.fields.date', ['name' => 'Due On', 'field' => 'due_on',])
    @include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount',])
    @include('partials.fields.submit')
@endsection
