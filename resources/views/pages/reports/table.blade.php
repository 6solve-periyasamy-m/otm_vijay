@extends('layout.master')

@section('title', 'View Reports')

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
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
                        <th scope="row">{{ $report['name'] }}</th>
                        <td>{{ $report['details'] }}</td>
                        <td>
                            <a href="{{ route($report['export'], ['extension' => 'csv']) }}" class="btn btn-outline-success"><i class="icon-list"></i> Export CSV</a>
                            <a href="{{ route($report['export'], ['extension' => 'xlsx']) }}" class="btn btn-outline-success"><i class="icon-chart"></i> Export XLSX</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
