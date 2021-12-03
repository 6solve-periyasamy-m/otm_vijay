@extends('layout.form', ['action' => $action,])

@section('title', 'Update ' . $templateName . ' Template')

@section('form-body')
    @include('partials.email.form', [
                'codes' => $codes,
                'body' => $body,])
@endsection
