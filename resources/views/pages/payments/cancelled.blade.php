@extends('layout.customer')

@section('title', 'Payment Cancelled')

@section('content')
<div class="inner_content">
    <x-customer.overview-top-bar title="Payment Cancelled" :search="false" /><br>
    <x-admin.section.card>
        <!-- <hr class="splitter"> -->
        <h4 class="col-md-12 mb-0">Payment Cancelled Successfully</h4>
        <!-- <hr class="splitter"> -->
        Your payment has been successfully cancelled. No changes have been made to your account, and no charges have been made to you.
    </x-admin.section.card>
</div>    
@endsection
