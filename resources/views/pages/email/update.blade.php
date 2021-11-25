@extends('layout.form', ['action' => route('test'),])

@section('title', 'Update Accommodation')

@section('form-body')
    @include('partials.fields.ckeditor', [
        'field' => 'body',
        'name' => 'Email Body',
    ])
    @include('partials.fields.submit')
@endsection
