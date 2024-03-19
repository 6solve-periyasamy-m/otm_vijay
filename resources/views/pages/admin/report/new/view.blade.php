@extends('layout.master')

@section('title', "View {$report->name} Report")

@section('content')
    <x-admin.section.card>
        <div class="float-end">
            <a href="{{ route('reports.advanced.edit', ['report' => $report,]) }}" class="btn btn-success">
                {{ Icon::edit() }}
                Edit Report
            </a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <livewire:admin.report.bespoke-report :report="$report" />
    </x-admin.section.card>
@endsection
