@extends('layout.form', ['action' => route('report.bespoke.results'),])

@section('title', 'Create New Report')

@section('form-body')
    @include('partials.reports.bespoke.list')
@endsection
