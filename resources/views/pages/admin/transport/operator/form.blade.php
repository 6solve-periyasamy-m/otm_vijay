@php
    /**
     * @var \App\Models\Transport\Operator|null $operator
     */
    $operator = $operator ?? null;
    $title = __('transport.operators.form.title.' . ($operator === null ? 'create' : 'update'));
    $route = $operator === null ?
        route('operators.store') :
        route('operators.update', ['operator' => $operator,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $operator?->name ?? null,])
    @include('partials.fields.submit')
@endsection