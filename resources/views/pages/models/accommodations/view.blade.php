@extends('layout.main')

@section('title', 'View Accommodation')

@section('content')
    Region Id: {{ $accommodation->region_id }}<br/>
    Title: {{ $accommodation->name }}<br/>
    Description: {{ $accommodation->description }}<br/>
    Audit Date: {{ $accommodation->audit_date }}<br/>
    Address: {{ $accommodation->address }}<br/>
    Currency: {{ $accommodation->currency }}<br/>
@endsection
