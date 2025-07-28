@extends('layout.master')

@php /** @var \App\Models\Merchandise\Merchandise $merchandise */ @endphp

@section('title', 'View Merchandise')

@push('footer-stack')
    <style>
        .image {
            background-image: url('{{ asset(setting('company.logo')) }}');
            background-repeat: no-repeat;
        }
        .item {
            margin: 5px;
        }
        .inventory-container {
            display: flex;
            flex-wrap: wrap;
        }
        .inventory {
            display: flex;
            flex-direction: column;
            width: max-content;
            margin: 5px 10px;
        }
    </style>
    <script>
        function update(selector) {
            selector = $(selector);
            let selected = selector.find(':selected');
            let box = selector.closest('.inventory');
            console.warn(selected.attr('purchase'));
            box.find('.purchase').text(selected.attr('purchase'));
            box.find('.sales').text(selected.attr('sales'));
            box.find('.stock').text(selected.attr('stock'));
            box.find('.used').text(selected.attr('used'));
            box.find('.available').text(selected.attr('available'));
            box.find('.edit-btn').attr('href', selected.attr('edit'));
            box.find('.delete-link').attr('action', selected.attr('delete'));
        }
    </script>
@endpush

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-xl-2">
                <img src="{{ $merchandise->asset }}" class="image large">
            </div>
            <div class="col-xl-10">
                <div class="row">
                    <div class="col-12">
                        <p>Merchandise Name</p>
                        <h6 class="fw-bold">{{ $merchandise->name }} ({{ $merchandise->type->name }})</h6>
                    </div>
                    @if(isset($merchandise->internal_notes))
                    <div class="col-12">
                        <p>Notes</p>
                        <h6 class="fw-bold">{{ $merchandise->internal_notes }}</h6>
                    </div>
                    @endif
                    <div class="col-12">
                        @if(isset($detailed) && $detailed)
                            <a href="{{ route('merchandise.view', ['merchandise' => $merchandise,]) }}" class="btn btn-info">
                                {{ Icon::overview() }}
                                Overview
                            </a>
                        @else
                            <a href="{{ route('merchandise.detailed', ['merchandise' => $merchandise,]) }}" class="btn btn-primary">
                                {{ Icon::list() }}
                                Detailed View
                            </a>
                        @endif
                        @can('create', \App\Models\Merchandise\Merchandise::class)
                            <a class="btn btn-info" title="Duplicate Merchandise" href="{{route('merchandise.duplicate', ['merchandise' => $merchandise,])}}">
                                {{ Icon::copy() }}
                                <span>Duplicate Merchandise</span>
                            </a>
                        @endcan
                        <a href="{{ route('merchandise.edit', ['merchandise' => $merchandise, 'view' => isset($detailed) && $detailed ? 'detailed' : 'overview']) }}" class="btn btn-success">
                            {{ Icon::edit() }}
                            Edit Merchandise
                        </a>
                        @can('delete', \App\Models\Merchandise\Merchandise::class)
                            <a href="{{ route('merchandise.archive', ['merchandise' => $merchandise]) }}" title="{{ $merchandise->archived ? "Restore" : "Archive" }}" class="btn btn-{{ $merchandise->archived ? "warning" : "danger" }}">
                                {{ Icon::archive() }}
                                <span>{{ $merchandise->archived ? "Restore" : "Archive" }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="splitter">
    <x-admin.section.card>
        <a class="btn btn-primary float-end" href="{{ route('merchandise.inventory.create', ['merchandise' => $merchandise,]) }}">
            {{ Icon::create() }}
            Create Inventory
        </a>
    </x-admin.section.card>
    <hr class="splitter">
    @if(isset($detailed) && $detailed)
        @include('partials.admin.merchandise.view.table', ['merchandise' => $merchandise])
    @else
        @include('partials.admin.merchandise.view.cards', ['merchandise' => $merchandise])
    @endif
@endsection
