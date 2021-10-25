@extends('layout.main')

@section('title', 'All Payment Methods')

@section('content')
    <a class="btn btn-primary" href="{{ route('payment-methods.create') }}">Create New</a>
    <table id="paymentMethod" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($paymentMethods as $paymentMethod)
            @include('partials.models.payment_methods.row', [
              'paymentMethod' => $paymentMethod,
              'name' => $paymentMethod->name,
            ])
        @endforeach
    </table>
@endsection
