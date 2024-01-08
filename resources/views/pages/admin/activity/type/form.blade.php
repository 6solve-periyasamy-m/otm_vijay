@php
    /**
     * @var \App\Models\Activity\ActivityType|null $activityType
     */
    $activityType = $activityType ?? null;
    $title = __('accommodation.inventory.board-type.form.title.' . ($activityType === null ? 'create' : 'update'));
    $route = $activityType === null ?
        route('activity-types.store') :
        route('activity-types.update', ['activityType' => $activityType,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $activityType?->name,])
    @include('partials.fields.submit')
@endsection