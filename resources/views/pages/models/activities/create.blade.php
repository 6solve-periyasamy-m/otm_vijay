@extends('layout.master')

@section('title', 'Create Activity')

@section('content')
    @include('partials.models.activities.form', ['action' => route('activities.store'),])
@endsection
