@extends('layout.master')

@section('title', 'View Agent')

@php
    /** @var \App\Models\Customer\Agent $agent */
@endphp

@section('content')
    <livewire:admin.agent.details :agent="$agent">
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Agent Details
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
         
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email Address</th>
                    </tr>
                    </thead>
                   
                        <tr>

                            <td>{{ $agent->first_name }}</td>
                            <td>{{ $agent->last_name }}</td>
                            <td>{{ $agent->email_address }}</td>
                        </tr>
     =
                </table>
            </x-admin.section.card>
        </div>
    </div>
@endsection
