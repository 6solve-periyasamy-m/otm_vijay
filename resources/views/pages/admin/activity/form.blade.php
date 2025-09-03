@php
    /**
     * @var \App\Models\Activity\Activity|null $activity
     */
    $activity = $activity ?? null;
    $title = __('activity.form.title.' . ($activity === null ? 'create' : 'update'));
    $route = $activity === null ?
        route('activities.store') :
        route('activities.update', ['activity' => $activity,]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    <x-livewire.input name="name" value="{{ $activity?->name }}" width="6" label="Name" required />
	<x-livewire.input.select.currency name="currency_id" label="Currency" value="{{ $activity?->currency_id }}" width="6" />
	<x-livewire.input.select.activity.activity-type name="activity_type_id" value="{{ $activity?->activity_type_id }}" label="Activity Type" width="4" required />
    <x-livewire.input.select.event.main name="event_id" value="{{ $activity?->event_id }}" label="Main Event" width="4" />
	<div class="form-group col-xl-4">
        @include('partials.fields.dropdown', ['name' => 'Activity Category', 'field' => 'activity_category', 'selected' => $activity?->activity_category->value, 'values' => \App\Models\Helper\Enum\ActivityCategory::toArray(),])
    </div>
    <x-livewire.input.select.activity.seating name="seating_id" value="{{ $activity?->seating_id }}" label="Seating" width="4" clear />
    <x-livewire.input.select.activity.session name="session_id" value="{{ $activity?->session_id }}" label="Session" width="4" clear />
    @include('partials.fields.prefab.addresses.switcher', ['address' => $activity?->address,])
    <x-livewire.ckeditor name="description" value="{{ $activity?->description }}" label="Description" />
	@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $activity?->internal_notes])
    <x-livewire.input name="field1" value="{{ $activity?->field1 }}" label="Label 1" width="6" />
    <x-livewire.input name="field2" value="{{ $activity?->field2 }}" label="Label 2" width="6" />
	@include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 3, 'value' => $activity?->image_url,])
    
    @include('partials.fields.submit')
@endsection 
