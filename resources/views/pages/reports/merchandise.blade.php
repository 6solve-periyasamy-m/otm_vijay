@extends('layout.master')

@section('title', ($title . ' ' ?? '') . 'Report Result')

@section('footer-script')
    <script type="text/javascript">
        let reportsTable;
        $(document).ready(function () { reportsTable = $('.report-table').DataTable({fixedHeader: true,  select: { style: "multi+shift" },}); });
        function fulfil() {
            let ids = [];
            reportsTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
                let row = reportsTable.row(rowIdx);
                ids.push($(row.node()).attr('id'));
            });
            if (ids.length <= 0) return alert('No components are selected');
            $.ajax({
                type: "POST",
                url: "{{ route('api.merchandise.fulfil') }}",
                dataType: "json",
                statusCode: {
                    200: function () { alert('Fulfilled Successfully'); location.reload(); },
                    403: function () { alert('Authentication has expired. Please refresh the page'); }
                },
                data: { "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
            });
        }
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <a class="btn btn-amber float-end" style="margin-left: 5px;" href="{{ $csvExport }}">
                {{ Icon::create() }}
                <span>Export as CSV</span>
            </a>
            <a class="btn btn-success float-end" style="margin-left: 5px;" href="{{ $xlsxExport }}">
                {{ Icon::create() }}
                <span>Export as Excel</span>
            </a>
            <a class="btn btn-info float-end" style="margin-left: 5px;" href="javascript:fulfil()">
                {{ Icon::fulfil() }}
                <span>Fulfil Selected</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include($tableView)
        </div>
    </div>
@endsection
