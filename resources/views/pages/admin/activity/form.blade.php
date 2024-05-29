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
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $activity?->name,'width' => 10,])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2, 'value' => $activity?->image_url,])
    <div class="form-group col-xl-4">
        @can('create', \App\Models\Activity\ActivityType::class)
            @include('partials.fields.selector.adder',
                        ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity?->activity_type_id,
                         'route' => 'activity-types', 'createRoute' => route('activity-types.create'),])
        @else
            @include('partials.fields.selector.default',
                    ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity?->activity_type_id,
                     'route' => 'activity-types',])
        @endcan
    </div>
    <x-livewire.input.select.event.main name="event_id" value="{{ $activity?->event_id }}" label="Main Event" width="4" />
    <div class="form-group col-xl-4">
        @include('partials.fields.dropdown', ['name' => 'Activity Category', 'field' => 'activity_category','selected' => $activity?->activity_category, 'values' => ["0" => "Normal Activity", "1" => "Main Activity"]])
    </div>
    @include('partials.fields.ckeditor', ['name' => 'Description', 'field' => 'description', 'value' => $activity?->description,])
    @include('partials.fields.prefab.addresses.switcher', ['address' => $activity?->address,])
    <x-livewire.input.select.currency name="currency_id" label="Currency" value="{{$activity?->currency_id}}" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $activity?->internal_notes])
    @include('partials.fields.submit')
@endsection 
