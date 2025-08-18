@extends('layout.master')

@section('title', 'Bulk Purge bookings')

@section('content')
    <x-admin.section.card>
        <div class="d-flex justify-content-start">
            <a href="{{ route('settings.edit') }}" class="btn btn-warning">{{ Icon::back() }} Back to Settings</a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="text-danger"><strong>Warning</strong>: Bulk Data Deletion Ahead</div>
        <div class="mt-2">You are about to permanently delete all selected empty booking records. This action is irreversible and cannot be undone.</div>
        <div class="mt-2">Please confirm that you have backed up any necessary data before proceeding.</div>
    </x-admin.section.card>

    <div class="imports">
        <div class="row">
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Purge all null booking leads</h4>
                    </div>
                    <div>
                        <livewire:admin.purge-bookings />
                    </div>
                    <p class="mt-3">Please make sure that all null booking leads are permanently deleted. This action is irreversible and cannot be undone. </p>
                </x-admin.section.card>
            </div>
            {{-- <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Purge null booking leads older than 180 days.</h4>
                    </div>
                    <div>
                        
                    </div>
                    <p class="mt-3">Please make sure that null booking leads older than 180 days are permanently deleted. This action is irreversible and cannot be undone. </p>
                </x-admin.section.card>
            </div> --}}
        </div>
    </div>
@endsection
