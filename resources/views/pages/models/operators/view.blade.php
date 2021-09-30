@extends('layout.main')

@section('title', 'View Operators')

@section('content')
Name: {{ $operator->name }}<br />
Notes: {{ $operator->notes }}<br />
@endsection
