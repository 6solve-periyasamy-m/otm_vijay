@extends('layout.master')

@section('title', ($title . ' ' ?? '') . 'Report Result')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () { $('.report-table').DataTable({fixedHeader: true}); });
    </script>
@endsection

@section('content')
    @include('partials.orders.reminder-frequencies', ['route' => 'reports.reminders'])
    <div class="card">
        <div class="card-body">
            Negative days means that the payment is overdue
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <a class="btn btn-amber float-end" style="margin-left: 5px;" href="{{ $csvExport }}">
                <i class="icon-plus"></i>
                <span>Export as CSV</span>
            </a>
            <a class="btn btn-success float-end" style="margin-left: 5px;" href="{{ $xlsxExport }}">
                <i class="icon-plus"></i>
                <span>Export as Excel</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include($tableView)
        </div>
    </div>
@endsection
