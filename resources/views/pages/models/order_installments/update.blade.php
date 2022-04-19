@extends('layout.form', ['action' => route('order-installments.update', ['order' => $order, 'orderInstallment' => $orderInstallment,]),])

@section('title', 'Update Order Installment')

@section('form-body')
    @include('partials.fields.date', ['name' => 'Due On', 'field' => 'due_on', 'value' => $orderInstallment?->due_on ?? null])
    @include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $orderInstallment?->amount ?? null])
    @include('partials.fields.submit')
@endsection
