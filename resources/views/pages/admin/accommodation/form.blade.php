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
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $accommodation?->name, 'width' => 6, 'required' => true])
	<x-livewire.input.select.currency name="currency_id" label="Currency" value="{{$accommodation?->currency_id}}" width="6" />
	@include('partials.fields.datetime', ['name' => 'Default Check In', 'field' => 'check_in', 'value' => old('check_in', $accommodation?->check_in), 'width' => 6 ])
    @include('partials.fields.datetime', ['name' => 'Default Check Out', 'field' => 'check_out', 'value' => old('check_out', $accommodation?->check_out), 'width' => 6 ])
	@include('partials.fields.prefab.addresses.switcher', ['address' => $accommodation?->address,])
    @include('partials.fields.ckeditor', ['name' => 'Description', 'field' => 'description', 'value' => $accommodation?->description,])
	@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $accommodation?->internal_notes])
    <h6 class="fs-5 fw-bold">e-Commerce</h6>    
	<div class="form-group col-xl-8">
        @can('create', \App\Models\Accommodation\AccommodationType::class)
            @include('partials.fields.selector.adder',
                        ['name' => 'Accommodation Type', 'field' => 'accommodation_type_id', 'value' => $accommodation?->accommodation_type_id,
                         'route' => 'accommodation-types', 'createRoute' => route('accommodation-types.create'), 'required' => true])
        @else
            @include('partials.fields.selector.default',
                    ['name' => 'Accommodation Type', 'field' => 'accommodation_type_id', 'value' => $accommodation?->accommodation_type_id,
                        'route' => 'accommodation-types', 'required' => true])
        @endcan
    </div>
	@include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 4, 'value' => $accommodation?->image_url])
    {{-- Gallery Upload --}}
    <div class="form-group col-md-6">
        <label for="gallery">Gallery Images</label>
        <input type="file" name="gallery[]" id="gallery" class="form-control" multiple>
        @if($accommodation && $accommodation->gallery->isNotEmpty())
            <div class="row">
                @foreach($accommodation->gallery as $image)
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                       <img src="{{ asset($image->file_path) }}" class="img-thumbnail w-100 h-100 object-fit-cover" >
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
	{{-- @include('partials.fields.date', ['name' => 'Audit Date', 'field' => 'audit_date', 'value' => $accommodation?->audit_date,])--}}
    @include('partials.fields.checkbox-multiselect', ['name' => 'Amenities', 'field' => 'amenities', 'options' => \App\Models\Accommodation\Amenity::pluck('name', 'id')->toArray(), 'selected' => $selected_amenities, ])
    @include('partials.fields.ckeditor', ['name' => 'Additional Description', 'field' => 'additional_description', 'value' => $accommodation?->additional_description,])
    @include('partials.fields.submit')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkIn = document.getElementById('check_in-input');
        const checkOut = document.getElementById('check_out-input');
        const auditDate = document.getElementById('audit_date-input');
        
        if (!checkIn || !checkOut || !auditDate) return;
        
        const now = new Date();
        auditDate.min = appFormatDate(now);
        checkIn.min = appFormatDateTime(now);
        checkIn.addEventListener('change', (e) => {
            const selected = new Date(e.target.value);
            if (isNaN(selected)) return;

            checkOut.min = e.target.value;

            const nextDay = new Date(selected);
            nextDay.setDate(nextDay.getDate() + 1);
            checkOut.value = appFormatDateTime(nextDay);
        });
    });
</script>
