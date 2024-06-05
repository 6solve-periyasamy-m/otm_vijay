@extends('layout.master')

@section('title', 'Email Templates')


@section('content')
    <x-admin.section.card>
        <div class="d-flex justify-content-start">
            <a href="{{ route('settings.edit') }}" class="btn btn-warning">{{ Icon::back() }} Back to Settings</a>
        </div>
    </x-admin.section.card>
    <div class="row">
        @foreach(\App\Repository\Mailing\MailRepository::getAvailableMail() as $template)
            <div class="col-xl-4 col-md-6 col-12">
                @include('partials.admin.system.mail.card', ['template' => $template,])
            </div>
        @endforeach
    </div>
@endsection