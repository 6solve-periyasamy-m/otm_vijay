@extends('layout.main')

@section('title', 'View Board Type')

@section('content')
    Board Type Name: {{ $boardType->board_type_name }}<br/>
@endsection
