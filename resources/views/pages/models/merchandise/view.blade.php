@extends('layout.master')

@section('title', 'View Merchandise')

@section('content')
    Name: {{ $merchandise->name }}<br/>
@endsection
