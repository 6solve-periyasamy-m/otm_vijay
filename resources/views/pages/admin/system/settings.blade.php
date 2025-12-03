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
    <x-admin.section.card>
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('settings.template') }}" class="btn btn-info">{{ Icon::template() }} Editor Templates</a>
            </div>
            <div>
                <a href="{{ route('settings.import') }}" class="btn btn-info">{{ Icon::import() }} Bulk Import</a>
            </div>
            <div>
                <a href="{{ route('settings.mail') }}" class="btn btn-info">{{ Icon::email() }} Email Templates</a>
            </div>
            <div>
                <a href="{{ route('settings.purge') }}" class="btn btn-info">{{ Icon::delete() }} Purge Bookings</a>
            </div>
            <div>
                <a href="{{ route('orders.reminders') }}" class="btn btn-info">{{ Icon::order() }} Order Reminders</a>
            </div>
        </div>
    </x-admin.section.card>
    <!-- Settings -->
    <div class="card">
        <div class="card-body" data-target="#settings" onclick="toggleAccordion(this)">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="fw-bold">
                        {{ Icon::maximize() }} System Settings
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
    <div class="collapse mx-1" id="settings">
        @include('partials.admin.system.settings.form')
    </div>
    <!-- Taxes -->
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
    <!-- Brands -->
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
    <div class="collapse mx-1" id="brands">
        <livewire:admin.system.brand.brand-list />
    </div>
    <!-- Faq's -->
     <div class="card">
        <div class="card-body" data-target="#faqs" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">{{ Icon::maximize() }} FAQ's</h4>
        </div>
    </div>
    <div class="collapse mx-1" id="faqs">
        <x-admin.section.card>
            <div class="flex float-end">
                <button class="btn btn-success" onclick="openModal('admin.system.faq.form')">
                    {{ Icon::create() }}Create New
                </button>
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <livewire:admin.system.faq.table />
        </x-admin.section.card>
    </div>
    <!-- Default Installments -->
    <div class="card">
        <div class="card-body" data-target="#default-installments" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::maximize() }} Default Installments
            </h4>
        </div>
    </div>
    <div class="collapse row mx-1" id="default-installments">
        <livewire:admin.system.installments.view />
    </div>
    <!-- Conversion Rates -->
    <div class="card">
        <div class="card-body" data-target="#conversions" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">{{ Icon::maximize() }} Conversion Rates</h4>
        </div>
    </div>
    <div class="collapse mx-1" id="conversions">
        <x-admin.section.card>
            <div class="flex float-end">
                <a class="btn btn-primary float-end" href="{{ route('export.conversion-rates', ['extension' => 'csv']) }}" style="margin-right: 5px">
                    {{ Icon::csv() }}
                    <span>Export to CSV</span>
                </a>
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
