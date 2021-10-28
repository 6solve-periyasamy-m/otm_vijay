@extends('layout.main')

@section('title', 'View PaymentInstallment')

@section('content')
Due On: {{ $paymentInstallment->due_on }}<br />
Amount: {{ $paymentInstallment->amount }}<br />
@endsection
