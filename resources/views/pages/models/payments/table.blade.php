@extends('layout.main')

@section('title', 'All Payments')

@section('content')
    <a class="btn btn-primary" href="{{ route('payments.create') }}">Create New</a>
    <table id="payment" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Order Id</th>
            <th scope="col">Payment Method Id</th>
            <th scope="col">Amount</th>
            <th scope="col">Reason</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($payments as $payment)
            @include('partials.models.payments.row', [
              'payment' => $payment,
              'order_id' => $payment->order_id,
              'payment_method_id' => $payment->payment_method_id,
              'amount' => $payment->amount,
              'reason' => $payment->reason,
            ])
        @endforeach
    </table>
@endsection
