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
    @can('create', \App\Models\Activity\ActivityType::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity?->activity_type_id,
                     'route' => 'activity-types', 'createRoute' => route('activity-types.create'),])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity?->activity_type_id,
                 'route' => 'activity-types',])
    @endcan
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $activity?->name,'width' => 10,])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2, 'value' => $activity?->image_url,])
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $activity?->description,])
    @include('partials.fields.prefab.addresses.switcher', [
        'name' => $activity?->address->name,
        'location_type_id' => $activity?->address->location_type_id,
        'address_line_1' => $activity?->address->address_line_1,
        'address_line_2' => $activity?->address->address_line_2,
        'town' => $activity?->address->town,
        'region' => $activity?->address->region,
        'country_id' => $activity?->address->country_id,
        'postcode' => $activity?->address->postcode,
    ])
    @include('partials.fields.selector.default',
        ['name' => 'Currency', 'field' => 'currency_id', 'value' => $activity?->currency, 'route' => 'currencies',])
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $activity?->internal_notes])
    @include('partials.fields.submit')
@endsection
