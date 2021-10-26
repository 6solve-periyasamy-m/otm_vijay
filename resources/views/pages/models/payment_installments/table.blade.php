@extends('layout.main')

@section('title', 'All Payment Installments')

@section('content')
<a class="btn btn-primary" href="{{ route('payment-installments.create') }}">Create New</a><table id="paymentInstallment" style="width: 100%;" class="table table-striped">
  <thead class="thead-dark">
  <tr>
    <th scope="col">Due On</th>
    <th scope="col">Amount</th>
    <th scope="col">Actions</th>
  </tr>
  </thead>
  @foreach($paymentInstallments as $paymentInstallment)
    @include('partials.models.payment_installments.row', [
      'paymentInstallment' => $paymentInstallment,
      'due_on' => $paymentInstallment->due_on,
      'amount' => $paymentInstallment->amount,
    ])
  @endforeach
</table>
@endsection
