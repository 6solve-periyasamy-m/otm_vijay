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
    <div class="card">
        <div class="card-body text-end">
            <button class="btn btn-primary text-white" onclick="showForm()">
                {{ Icon::create() }}
                Create New
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <livewire:admin.supplier.table />
        </div>
    </div>
@endsection
