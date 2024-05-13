@extends('layout.master')

@section('title', 'All Suppliers')

@push('footer-stack')
    <script type="text/javascript">
        function showForm(id = null) {
            Livewire.emit('openModal', 'admin.supplier.form', {'supplier': id});
        }
    </script>
@endpush

@section('content')
    <x-admin.section.card>
        <div class="text-end">
            <button class="btn btn-primary text-white" onclick="showForm()">
                {{ Icon::create() }}
                Create New
            </button>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <livewire:admin.supplier.table />
    </x-admin.section.card>
@endsection
