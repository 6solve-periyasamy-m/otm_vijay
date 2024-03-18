@extends('layout.master')

@php
/**
 * @var \App\Report\BespokeReport $report
 * @var \App\Models\System\BespokeReport|null $update
 */
$update = $update ?? null;
$title = isset($update) ? 'Update Bespoke Report' : 'Create Bespoke Report';
$route = isset($update) ? route('reports.advanced.update', ['report' => $update, ]) : route('reports.advanced.store');
$fields = $update?->fields ?? [];
@endphp

@section('title', $title)

@section('content')
    <form action="{{ $route }}" method="post">
        @csrf
        <x-admin.section.card>
            <div class="row">
                <x-admin.input name="name" width="5" value="{{ $update?->name }}">Name</x-admin.input>
                <x-admin.input name="description" width="5" value="{{ $update?->description }}">Description</x-admin.input>
                <div class="col-xl-2">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </x-admin.section.card>
        @php
            /**
             * @var string $key
             * @var \App\Report\ColumnDefinition $column
             */
             $parent = null;
        @endphp
        <div class="row">
            @foreach($report->allColumns() as $key => $column)
                @if($parent !== strtok($key, '_'))
                    @php $parent = strtok($key, '_');  @endphp
                    <div class="col-12">
                        <x-admin.section.card>
                            <h1>{{ ucwords($parent) }}</h1>
                        </x-admin.section.card>
                    </div>
                @endif
                <div class="col-xl-3 col-md-4 col-6">
                    <x-admin.section.card>
                        <div class="click-toggle">
                            <div class="row">
                                <div class="col-11">
                                    <h4 class="fw-bold">{{ $column->name }}</h4>
                                    <span>{{ $column->description }}</span>
                                </div>
                                <div class="col-1">
                                    <div class="mx-auto my-auto">
                                        <input type="checkbox" name="keys[{{ $key }}]" @checked(old('keys[{{ $key }}]', in_array($key, $fields))) />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-admin.section.card>
                </div>
            @endforeach
        </div>
    </form>
@endsection

@push('footer-stack')
    <script type="text/javascript">
        $('.click-toggle').on('click', function (event) {
            if (!$(event.target).is(':checkbox')) {
                let checkbox = $(this).find("input[type='checkbox']");
                checkbox.prop("checked", !checkbox.prop("checked"));
                event.stopPropagation();
            }
        });
    </script>
@endpush