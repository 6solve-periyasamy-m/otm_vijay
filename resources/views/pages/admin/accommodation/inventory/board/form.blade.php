@php
    /**
     * @var \App\Models\Accommodation\BoardType|null $boardType
     */
    $boardType = $boardType ?? null;
    $title = __('accommodation.inventory.board-type.form.title.' . ($boardType === null ? 'create' : 'update'));
    $route = $boardType === null ?
        route('board-types.store') :
        route('board-types.update', ['boardType' => $boardType,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $boardType?->name,])
    @include('partials.fields.submit')
@endsection