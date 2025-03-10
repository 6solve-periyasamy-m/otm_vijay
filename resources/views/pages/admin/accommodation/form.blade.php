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
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $accommodation?->name, 'width' => 8])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 4, 'value' => $accommodation?->image_url])
    <div class="form-group">
        <p><label for="images">Gallery Images</label>
        <input type="file" name="images[]" multiple class="form-control"></p>
        @if($accommodation && $accommodation->gallery)
            <div class="row">
                @foreach($accommodation->gallery as $image)
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                       <img src="{{ asset($image->image_url) }}" class="img-thumbnail w-100 h-100 object-fit-cover" >
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @include('partials.fields.ckeditor', ['name' => 'Description', 'field' => 'description', 'value' => $accommodation?->description,])
    @include('partials.fields.date', ['name' => 'Audit Date', 'field' => 'audit_date', 'value' => $accommodation?->audit_date,])
    @include('partials.fields.datetime', ['name' => 'Default Check In', 'field' => 'check_in', 'value' => $accommodation?->check_in, 'width' => 6 ])
    @include('partials.fields.datetime', ['name' => 'Default Check In', 'field' => 'check_out', 'value' => $accommodation?->check_out, 'width' => 6 ])
    @include('partials.fields.prefab.addresses.switcher', ['address' => $accommodation?->address,])
    <x-livewire.input.select.currency name="currency_id" label="Currency" value="{{$accommodation?->currency_id}}" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $accommodation?->internal_notes])
    @include('partials.fields.submit')
@endsection
