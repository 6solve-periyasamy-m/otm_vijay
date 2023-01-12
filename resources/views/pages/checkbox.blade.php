@extends('layout.master')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-body">
        @include('partials.fields.btn-checkbox', ['field' => 'checkbox', 'value' => true,])
    </div>
</div>
@endsection
