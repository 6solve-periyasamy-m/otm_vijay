@php
    /**
     * @var \App\Models\Accommodation\Accommodation|null $accommodation
     */
    $accommodation = $accommodation ?? null;
    $title = __('accommodation.form.title.' . ($accommodation === null ? 'create' : 'update'));
    $route = $accommodation === null ?
        route('accommodations.store') :
        route('accommodations.update', ['accommodation' => $accommodation,]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $accommodation?->name, 'width' => 10])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2, 'value' => $accommodation?->image_url])
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $accommodation?->description,])
    @include('partials.fields.date', ['name' => 'Audit Date', 'field' => 'audit_date', 'value' => $accommodation?->audit_date,])
    @include('partials.fields.prefab.addresses.switcher', [
                        'location_type_id' => $accommodation?->address->location_type_id,
                        'address_line_1' => $accommodation?->address->address_line_1,
                        'address_line_2' => $accommodation?->address->address_line_2,
                        'town' => $accommodation?->address->town,
                        'region' => $accommodation?->address->region,
                        'country_id' => $accommodation?->address->country_id,
                        'postcode' => $accommodation?->address->postcode,
                    ])
    @include('partials.fields.selector.default',
        ['name' => 'Currency', 'field' => 'currency_id', 'value' => $accommodation?->currency, 'route' => 'currencies',])
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $accommodation?->internal_notes])
    @include('partials.fields.submit')
@endsection
