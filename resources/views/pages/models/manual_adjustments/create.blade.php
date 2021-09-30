@extends('layout.main')

@section('title', 'Create Manual Adjustments')

@section('content')
  @include('partials.models.manual_adjustments.form', ['action' => route('manual_adjustments.store'),])
@endsection
