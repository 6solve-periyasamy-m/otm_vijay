@extends('layout.main')

@section('title', 'Create Order Customer Adjustment')

@section('content')
    @include('partials.models.order_customer_adjustments.form', ['action' => route('order-customer-adjustments.store'),])
@endsection
