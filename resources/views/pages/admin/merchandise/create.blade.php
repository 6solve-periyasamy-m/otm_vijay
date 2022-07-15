@extends('layout.form', ['action' => route('merchandise.store'), 'multipart' => true,])

@section('title', 'Create New Merchandise')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'width' => 6])
    @include('partials.fields.selector.adder', ['name' => 'Merchandise Type', 'field' => 'type', 'width' => 5, 'route' => 'merchandise-types', 'createRoute' => route('merchandise.type.create')])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 1])
    @include('partials.fields.textarea', ['name' => 'Notes', 'field' => 'notes',])
    @include('partials.fields.submit')
@endsection
