@extends('layout.main')

@section('title', 'View Airline')

@section('content')
    Name: {{ $airline->name }}<br/>
@endsection
