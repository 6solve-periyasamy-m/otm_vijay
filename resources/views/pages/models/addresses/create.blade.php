@extends('layout.form', ['action' => route('addresses.store', ['addressParent' => $addressParent,]),])

@section('title', 'Create Address')

@section('form-body')
    @include('partials.models.addresses.form', ['address' => null,])
@endsection
