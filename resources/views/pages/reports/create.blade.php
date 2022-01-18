@extends('layout.form', ['action' => route('reports.bespoke.temporary.show'),])

@section('title', 'Create New Report')

@section('form-body')
    <input type="hidden" name="parent" value="{{ $parent ?? $report->parent }}">
    @include('partials.reports.bespoke.list')
@endsection
