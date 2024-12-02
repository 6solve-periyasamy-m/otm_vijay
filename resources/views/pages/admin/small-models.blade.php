@extends('layout.master')

@section('title', 'Small Model Manager')

@section('content')
    <div class="card">
        <div class="card-body" data-target="#accommodation" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Accommodation
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="accommodation">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\RoomTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\BoardTypeRepository::class])
        </div>
        <div class="col-xl-4">
            <x-admin.section.card>
                <slot:header>
                    <div class="d-flex justify-content-between">
                        <h4 class="fw-bold">Room Categories</h4>
                        <button class="btn btn-primary" onclick="openModal('admin.accommodation.room-category.form')">
                            {{ Icon::create() }}
                            Create New
                        </button>
                    </div>

                </slot:header>
                <livewire:admin.accommodation.room-category.table />
            </x-admin.section.card>
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#activity" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Activity
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="activity">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Activity\ActivityTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Activity\TicketTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Location\LocationTypeRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#flight" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Flight
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="flight">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Flight\AirlineRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Flight\AirportRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\TravelClassRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#transport" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Transport
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="transport">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Transport\OperatorRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Transport\TransportTypeRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#merchandise" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Merchandise
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="merchandise">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Merchandise\MerchandiseTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Merchandise\MerchandiseSizeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Merchandise\VariantRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#tour" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Tour
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="tour">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Tour\TourCategoryRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#customer" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Customer
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="customer">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Customer\HatSizeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Customer\TShirtSizeRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#quote" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} Quote
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="quote">
        <div class="col-6">
            <x-admin.section.card>
                <x-slot:title>Quote Section types</x-slot:title>
                <livewire:admin.quote.section.type.table />
            </x-admin.section.card>
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#system" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} System
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="system">
        <div class="col-6">
            <x-admin.section.card>
                <x-slot:title>Countries</x-slot:title>
                <livewire:admin.location.country.table />
            </x-admin.section.card>
        </div>
        <div class="col-6">
            <x-admin.section.card>
                <x-slot:title>Currency</x-slot:title>
                <livewire:admin.location.currency.table />
            </x-admin.section.card>
        </div>
        <div class="col-6">
            <x-admin.section.card>
                <x-slot:header>
                    <div class="flex justify-between">
                        <div><h4 class="fw-bold">Payment Methods</h4></div>
                        <div><button class="btn btn-primary" onclick="openModal('admin.system.payment-method.form')">{{ \Icon::create() }} Create New</button></div>
                    </div>
                </x-slot:header>
                <livewire:admin.system.payment-method.table />
            </x-admin.section.card>
        </div>
    </div>
@endsection
