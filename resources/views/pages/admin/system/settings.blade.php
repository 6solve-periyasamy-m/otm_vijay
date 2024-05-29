@extends('layout.master')

@section('title', 'Edit System Settings')

@push('footer-stack')
<script type="text/javascript">
    function showBrandForm(event) {
        Livewire.emit('openModal', 'admin.system.brand.form', {!! json_encode(['brand' => null,]) !!});
        event.stopPropagation();
    }
</script>
@endpush

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
        <div class="card-body" data-target="#taxes" onclick="toggleAccordion(this)">
            <div class="flex justify-between">
                <h4 class="fw-bold">
                    {{ Icon::maximize() }} Tax Brackets
                </h4>
                <div>
                    <a href="#" onclick="openModal('admin.system.tax-bracket.form')"
                       class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::save() }} Create Tax Bracket
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse row mx-1" id="taxes">
        <livewire:admin.system.tax-bracket.tiles />
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
                    <button onclick="showBrandForm(event)" class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::list() }} Create new Brand
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse show mx-1" id="brands">
        <livewire:admin.system.brand.brand-list />
    </div>
    <div class="card">
        <div class="card-body" data-target="#import" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::maximize() }} Bulk Import
            </h4>
        </div>
    </div>
    <div class="collapse row mx-1" id="import">
        @include('partials.admin.system.import')
    </div>
    <div class="card">
        <div class="card-body" data-target="#default-installments" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::maximize() }} Default Installments
            </h4>
        </div>
    </div>
    <div class="row mx-1" id="default-installments">
        <livewire:admin.system.installments.view />
    </div>
    <div class="card">
        <div class="card-body" data-target="#conversions" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">{{ Icon::maximize() }} Conversion Rates</h4>
        </div>
    </div>
    <div class="collapse show mx-1" id="conversions">
        <x-admin.section.card>
            <div class="flex float-end">
                <button class="btn btn-success" onclick="openModal('admin.system.conversion.form')">
                    {{ Icon::create() }}Create New
                </button>
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <livewire:admin.system.conversion.table />
        </x-admin.section.card>
    </div>
@endsection
