@extends('layout.master')

@php
    /**
     * @var \App\Models\Customer\Organization[] $organizations
     */
@endphp

@section('title', 'All Organizations')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#organizations').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    @can('create', \App\Models\Quote\Quote::class)
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary float-end" href="{{ route('organizations.create') }}">
                    <x-icon icon="plus" />
                    <span>Create New</span>
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <table id="organizations" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Customers</th>
                    <th scope="col">Orders</th>
                    <th scope="col">Quotes</th>
                    <th scope="col">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($organizations as $organization)
                    <tr>
                        <td><a href="{{ route('organizations.view', ['organization' => $organization,]) }}">{{ $organization->name }}</a></td>
                        <td>{{ $organization->contact_email }}</td>
                        <td>{{ $organization->customers_count }}</td>
                        <td>{{ $organization->orders_count }}</td>
                        <td>{{ $organization->quotes_count }}</td>
                        <td class="actions">
                            @can('update', \App\Models\Customer\Organization::class)
                                <a href="{{route('organizations.edit', ['organization' => $organization,])}}" class="btn btn-sm btn-outline-success mb-1">
                                    <x-icon icon="note" />
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <x-icon icon="note" />
                                </span>
                            @endcan
                            @can('delete', \App\Models\Customer\Organization::class)
                                <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                                   onclick="event.preventDefault();document.getElementById('organization-{{ $organization->id }}-delete').submit();">
                                    <x-icon icon="trash" />
                                </a>
                                <form id="organization-{{ $organization->id }}-delete" action="{{ route('organizations.delete', ['organization' => $organization,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <x-icon icon="trash" />
                                </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
