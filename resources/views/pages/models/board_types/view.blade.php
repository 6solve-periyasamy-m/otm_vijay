@extends('layout.main')

@section('title', 'View Board Types')

@section('content')
Board Type Name: {{ $boardType->board_type_name }}<br />
@endsection
