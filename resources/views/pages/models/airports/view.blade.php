@extends('layout.main')

@section('title', 'View Airports')

@section('content')
    Name: {{ $airport->name }}<br/>
    Iata Code: {{ $airport->iata_code }}<br/>
@endsection
