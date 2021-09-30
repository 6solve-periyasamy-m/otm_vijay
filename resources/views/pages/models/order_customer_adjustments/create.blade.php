@extends('layout.main')

@section('title', 'Create Order Customer Adjustments')

@section('content')
  @include('partials.models.order_customer_adjustments.form', ['action' => route('order_customer_adjustments.store'),])
@endsection
