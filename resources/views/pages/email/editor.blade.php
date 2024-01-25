@php
    /** @var \App\Mail\Storage\TemplatedMail $mail */
@endphp
@extends('layout.form', ['action' => $mail->getUpdateUrl(),])

@section('title', 'Update ' . $mail->getCode() . ' Template')

@section('form-body')
    @php
        /** @var \App\Mail\Storage\TemplatedMail $mail */
    @endphp
    <div class="col-12 col-xl-8">
        @include('partials.fields.text', [
            'field' => 'subject',
            'name' => 'Email Subject',
            'value' => $mail->getSubject()
        ])
        @include('partials.fields.ckeditor', [
            'field' => 'body',
            'name' => 'Email Body',
            'value' => $mail->getBody()
        ])
        @include('partials.fields.submit')
        <a class="d-inline-flex btn btn-amber" href="{{ $mail->getDemoUrl() }}">Demo Email</a>
    </div>
    <div class="col-12 col-xl-4">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Code</th>
                <th scope="col">Example</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mail->getShortcodes() as $code => $example)
                <tr>
                    <th scope="row">[{{$code}}]</th>
                    <td>{{$example}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
