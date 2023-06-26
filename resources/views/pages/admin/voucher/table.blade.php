@extends('layout.master')

@section('title', 'All Vouchers')

@push('footer-stack')
    <script type="text/javascript">
        function showForm(id = null) {
            Livewire.emit('openModal', 'admin.voucher.form', {'voucher': id});
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
            <livewire:admin.voucher.table />
        </div>
    </div>
@endsection
