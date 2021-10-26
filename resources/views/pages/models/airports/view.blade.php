@extends('layout.master')

@section('title', 'View Airport')

@section('content')
<div class="otm-callout">
    <p>Name</p>
    <h4 class="fw-bold">{{ $airport->name }}</h4>    
    <p>Iata Code</p>
    <h4 class="fw-bold">{{ $airport->iata_code }}</h4>
</div>
@endsection
