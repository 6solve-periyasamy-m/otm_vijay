@extends('layout.division')

@section('listing')
Payment Schedule
@foreach($payments as $payment)
<div class="listing">
    <div class="listing__title">{{$payment->title}}</div><div class="listing-title">{{$payment->amount}}</div>
    @foreach($payment->installments as $installment)
        <div class="listing__installment">{{$installment->deposit}}</div>
        <div class="listing__installment">{{$installment->period}}</div>
        <div class="listing__installment">{{$installment->installments}}</div>
    @endforeach
</div>
@endforeach

@endsection