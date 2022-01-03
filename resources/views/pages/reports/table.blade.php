@extends('layout.master')

@section('title', 'View Reports')

@push('header-stack')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#reports').DataTable({fixedHeader: true,});
        });
    </script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="reports">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <th scope="row"><a href="{{ route($report['view']) }}">{{ $report['name'] }}</a></th>
                        <td>{{ $report['details'] }}</td>
                        <td>
                            <a href="{{ route($report['export'], ['extension' => 'csv']) }}" class="btn btn-outline-warning"><i class="icon-list"></i> Export CSV</a>
                            <a href="{{ route($report['export'], ['extension' => 'xlsx']) }}" class="btn btn-outline-success"><i class="icon-chart"></i> Export XLSX</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
