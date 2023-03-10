@extends('layout.master')

@section('title', 'Edit System Settings')

@section('content')
    <div class="card">
        <div class="card-body" data-target="#settings" onclick="toggleAccordion(this)">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="fw-bold">
                        {{ Icon::minimize() }} System Settings
                    </h4>
                </div>
                <div>
                    <a href="#" onclick="event.preventDefault();$('#settings-form').submit()"
                       class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::save() }} Update Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse show mx-1" id="settings">
        @include('partials.admin.system.settings.form')
    </div>
    <div class="card">
        <div class="card-body" data-target="#reminders" onclick="toggleAccordion(this)">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="fw-bold">
                        {{ Icon::maximize() }} Order Reminders
                    </h4>
                </div>
                <div>
                    <a href="{{ route('orders.reminders') }}"
                       class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::list() }}View Due Reminders
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse mx-1" id="reminders">
        @include('partials.orders.reminder.authorize')
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
    <div class="card">
        <div class="card-body" data-target="#brands" onclick="toggleAccordion(this)">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="fw-bold">
                        {{ Icon::maximize() }} Company Brands
                    </h4>
                </div>
                <div>
                    <button onclick="Livewire.emit('openModal', 'brand-form');" class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::list() }} Create new Brand
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse show row mx-1" id="brands">
        <div class="col-xl-4">
            @include('partials.admin.system.brand.card', ['brand' => \App\Models\System\Brand::getSystemBrand(),])
        </div>
        @foreach(\App\Models\System\Brand::all() as $brand)
            <div class="col-xl-4">
                @include('partials.admin.system.brand.card', ['brand' => $brand,])
            </div>
        @endforeach
    </div>
@endsection
