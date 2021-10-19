@extends('layout.main')

@section('title', 'All Order Customer Adjustments')

@section('content')
    <a class="btn btn-primary" href="{{ route('order-customer-adjustments.create', ['order' => $order, 'orderCustomer' => $orderCustomer, ]) }}">Create New</a>
    <table id="orderCustomerAdjustment" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Order Customer Id</th>
            <th scope="col">Amount</th>
            <th scope="col">Reason</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($orderCustomerAdjustments as $orderCustomerAdjustment)
            @include('partials.models.order_customer_adjustments.row', [
              'orderCustomerAdjustment' => $orderCustomerAdjustment,
              'order_customer_id' => $orderCustomerAdjustment->order_customer_id,
              'amount' => $orderCustomerAdjustment->amount,
              'reason' => $orderCustomerAdjustment->reason,
            ])
        @endforeach
    </table>
@endsection
