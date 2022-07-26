@extends('layout.form', ['action' => (isset($merchandise) ? route('merchandise.update', ['merchandise' => $merchandise, 'view' => $view ?? 'overview',]) : route('merchandise.store')), 'multipart' => true,])

@section('title', (isset($merchandise) ? 'Update' : 'Create New') . ' Merchandise')

@php /** @var \App\Models\Merchandise\Merchandise|null $merchandise */ $merchandise = $merchandise ?? null; @endphp

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'width' => 6, 'value' => $merchandise?->name])
    @include('partials.fields.selector.adder', ['name' => 'Merchandise Type', 'field' => 'type', 'width' => 5, 'route' => 'merchandise-types', 'createRoute' => route('merchandise.type.create'), 'value' => $merchandise?->merchandise_type_id ?? null])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 1])
    @include('partials.fields.textarea', ['name' => 'Notes', 'field' => 'notes', 'value' => $merchandise?->notes,])
    @include('partials.fields.submit')
@endsection
