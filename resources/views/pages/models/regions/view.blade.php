@extends('layout.main')

@section('title', 'View Regions')

@section('content')
    Country Id: {{ $region->country_id }}<br/>
    Name: {{ $region->name }}<br/>
@endsection
