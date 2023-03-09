@extends('layout.master')

@section('title', 'Edit System Settings')

@section('content')
    <div class="card">
        <div class="card-body" data-target="#settings" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} System Settings
            </h4>
        </div>
    </div>
    <div class="collapse show mx-1" id="settings">
        @include('partials.admin.system.settings.form')
    </div>
    <div class="card">
        <div class="card-body" data-target="#mail" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::maximize() }} Mail Templates
            </h4>
        </div>
    </div>
    <div class="collapse row mx-1" id="mail">
        @foreach(\App\Repository\Mailing\MailRepository::getAvailableMail() as $template)
            <div class="col-xl-6">
                @include('partials.admin.system.mail.card', ['template' => $template,])
            </div>
        @endforeach
    </div>
@endsection
