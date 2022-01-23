@extends('layout.form', ['action' => route('reports.bespoke.update', ['report' => $report,]),])

@section('title', 'Edit Report')

@section('form-body')
    <input type="hidden" name="parent" value="{{ $parent ?? $report->parent }}">
    @include('partials.reports.bespoke.list')
@endsection
