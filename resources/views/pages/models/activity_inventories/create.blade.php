@extends('layout.main')

@section('title', 'Create Activity Inventories')

@section('content')
  @include('partials.models.activity_inventories.form', ['action' => route('activity_inventories.store'),])
@endsection
