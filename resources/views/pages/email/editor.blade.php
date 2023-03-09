@php/** @var \App\Mail\Storage\TemplatedMail $mail */@endphp
@extends('layout.form', ['action' => $mail->getUpdateUrl(),])

@section('title', 'Update ' . $mail->getCode() . ' Template')

@section('form-body')
    @include('partials.email.form', ['mail' => $mail,])
@endsection
