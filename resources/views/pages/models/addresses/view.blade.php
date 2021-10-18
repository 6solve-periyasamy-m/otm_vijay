@extends('layout.main')

@section('title', 'View Address')

@section('content')
    Address Line 1: {{ $address->address_line_1 }}<br/>
    Address Line 2: {{ $address->address_line_2 }}<br/>
    Town: {{ $address->town }}<br/>
    Region: {{ $address->region }}<br/>
    Country: {{ $address->country }}<br/>
    Postcode: {{ $address->postcode }}<br/>
@endsection
