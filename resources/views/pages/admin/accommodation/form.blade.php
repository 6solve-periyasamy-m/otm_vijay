@php
    /**
     * @var \App\Models\Accommodation\Accommodation|null $accommodation
     */
    $accommodation = $accommodation ?? null;
    $title = __('accommodation.form.title.' . ($accommodation === null ? 'create' : 'update'));
    $route = $accommodation === null ?
        route('accommodations.store') :
        route('accommodations.update', ['accommodation' => $accommodation,]);
        $selected_amenities = $accommodation ? $accommodation->amenities->pluck('id')->toArray() : [];
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $accommodation?->name, 'width' => 8])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 4, 'value' => $accommodation?->image_url])
    @include('partials.fields.ckeditor', ['name' => 'Description', 'field' => 'description', 'value' => $accommodation?->description,])
    @include('partials.fields.date', ['name' => 'Audit Date', 'field' => 'audit_date', 'value' => $accommodation?->audit_date,])
    @include('partials.fields.datetime', ['name' => 'Default Check In', 'field' => 'check_in', 'value' => $accommodation?->check_in, 'width' => 6 ])
    @include('partials.fields.datetime', ['name' => 'Default Check In', 'field' => 'check_out', 'value' => $accommodation?->check_out, 'width' => 6 ])
    <div class="form-group col-xl-9">
        @include('partials.fields.ckeditor', ['name' => 'Additional Description', 'field' => 'additional_description', 'value' => $accommodation?->additional_description,])
    </div>
    <div class="form-group col-xl-3">
        @can('create', \App\Models\Accommodation\AccommodationType::class)
            @include('partials.fields.selector.adder',
                        ['name' => 'Accommodation Type', 'field' => 'accommodation_type_id', 'value' => $accommodation?->accommodation_type_id,
                         'route' => 'accommodation-types', 'createRoute' => route('accommodation-types.create'),])
        @else
            @include('partials.fields.selector.default',
                    ['name' => 'Accommodation Type', 'field' => 'accommodation_type_id', 'value' => $accommodation?->accommodation_type_id,
                        'route' => 'accommodation-types',])
        @endcan
    </div>

    @include('partials.fields.checkbox-multiselect', ['name' => 'Amenities', 'field' => 'amenities', 'options' => \App\Models\Accommodation\Amenity::pluck('name', 'id')->toArray(), 'selected' => $selected_amenities, ])
    @include('partials.fields.prefab.addresses.switcher', ['address' => $accommodation?->address,])
    <x-livewire.input.select.currency name="currency_id" label="Currency" value="{{$accommodation?->currency_id}}" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $accommodation?->internal_notes])
    @include('partials.fields.submit')
@endsection
