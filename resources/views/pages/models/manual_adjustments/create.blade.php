@extends('layout.main')

@section('title', 'Create Manual Adjustment')

@section('content')
    @include('partials.models.manual_adjustments.form', ['action' => route('manual-adjustments.store'),])
@endsection
