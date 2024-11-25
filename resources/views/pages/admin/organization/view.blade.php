@extends('layout.master')

@section('title', 'View Organization')

@php
    /** @var \App\Models\Customer\Organization $organization */
@endphp

@section('content')
    <livewire:admin.organization.details :organization="$organization">
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Organization Orders
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Booking Reference</th>
                        <th scope="col">Tour Name</th>
                        <th scope="col">Lead</th>
                        <th scope="col">Ordered On</th>
                        <th scope="col">Tour Cost</th>
                        <th scope="col">Order Status</th>
                    </tr>
                    </thead>
                    @foreach($organization->orders as $order)
                        <tr>
                            <th scope="row"><a href="{{ route('orders.view', ['order' => $order,]) }}">{{ $order->booking_reference }}</a></th>
                            <td>{{ $order->tour->name }}</td>
                            <td>{{ $order->leadBooker->customer_name }}</td>
                            <td>{{ f_datetime($order->ordered_on) }}</td>
                            <td>{{ f_currency($order->total) }}</td>
                            <td><h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6></td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Organization Quotes
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Quote Reference</th>
                        <th scope="col">Name</th>
                        <th scope="col">Lead</th>
                        <th scope="col">Expiry Date</th>
                        <th scope="col">Quote Status</th>
                    </tr>
                    </thead>
                    @foreach($organization->quotes as $quote)
                        <tr>
                            <th scope="row"><a href="{{ route('quotes.view', ['quote' => $quote,]) }}">{{ $quote->ref }}</a></th>
                            <td>{{ $quote->name }}</td>
                            <td>{{ $quote->leadTraveller->name }}</td>
                            <td>{{ f_date($quote->expires) }}</td>
                            <td>{{ $quote->status->badge() }}</td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <hr class="splitter" />
        @if($organization->agents)
        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>
                    Organization Agents
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Orders</th>
                        <th scope="col">Quotes</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($organization->agents as $agent)
                        <tr wire:key="{{$agent->id}}">
                            <td>{{ $agent->first_name . ' ' . $agent->last_name }}</td>
                            <td>{{ $agent->email }}</td>
                            <td>{{ $agent->orders()->count() }}</td>
                            <td>{{ $agent->quotes()->count() }}</td>
                            <td style="width: 10px">
                                <button class="edit-button" onclick="openModal('admin.agent.form', {'agent': {{$agent->id}},})">{{Icon::edit()}}</button>
                            </td>
                            <td style="width: 10px">
                                <form method="post" action="/admin/organizations/{{$organization->id}}/agents/{{$agent->id}}/delete-agent">
                                    @csrf
                                    <button class="delete-button" type="submit">
                                        {{Icon::delete()}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button onclick="openModal('admin.agent.form', {'organization': {{$organization->id}},})" class="btn btn-success">
                    {{ Icon::edit() }}
                    Add Agent
                </button> 
            </x-admin.section.card>
        </div>
        @endif
        @if($organization->agentOrders) 
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Agent Orders
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Booking Reference</th>
                        <th scope="col">Tour Name</th>
                        <th scope="col">Lead</th>
                        <th scope="col">Ordered On</th>
                        <th scope="col">Tour Cost</th>
                        <th scope="col">Order Status</th>
                    </tr>
                    </thead>
                    @foreach($organization->agentOrders as $order)
                        <tr>
                            <th scope="row"><a href="{{ route('orders.view', ['order' => $order,]) }}">{{ $order->booking_reference }}</a></th>
                            <td>{{ $order->tour->name }}</td>
                            <td>{{ $order->leadBooker->customer_name }}</td>
                            <td>{{ f_datetime($order->ordered_on) }}</td>
                            <td>{{ f_currency($order->total) }}</td>
                            <td><h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6></td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        @endif
        @if($organization->agentQuotes)
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Agent Quotes
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Quote Reference</th>
                        <th scope="col">Name</th>
                        <th scope="col">Lead</th>
                        <th scope="col">Expiry Date</th>
                        <th scope="col">Quote Status</th>
                    </tr>
                    </thead>
                    @foreach($organization->agentQuotes as $quote)
                        <tr>
                            <th scope="row"><a href="{{ route('quotes.view', ['quote' => $quote,]) }}">{{ $quote->ref }}</a></th>
                            <td>{{ $quote->name }}</td>
                            <td>{{ $quote->leadTraveller->name }}</td>
                            <td>{{ f_date($quote->expires) }}</td>
                            <td>{{ $quote->status->badge() }}</td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        @endif
        <hr class="splitter" />
        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>
                    Organization Members (agents as non-travelling lead booker)
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Orders</th>
                        <th scope="col">Quotes</th>
                    </tr>
                    </thead>
                    @foreach($organization->customers as $customer)
                        <tr>
                            <th scope="row"><a href="{{ route('customers.view', ['customer' => $customer,]) }}">{{ $customer->full_name }}</a></th>
                            <td>{{ $customer->email_address }} ({{ $customer->mobile_number }})</td>
                            <td>{{ $customer->orders()->count() }}</td>
                            <td>{{ $customer->quoteProspects()->count() }}</td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Member Orders
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Booking Reference</th>
                        <th scope="col">Tour Name</th>
                        <th scope="col">Lead</th>
                        <th scope="col">Ordered On</th>
                        <th scope="col">Tour Cost</th>
                        <th scope="col">Order Status</th>
                    </tr>
                    </thead>
                    @foreach($organization->memberOrders as $order)
                        <tr>
                            <th scope="row"><a href="{{ route('orders.view', ['order' => $order,]) }}">{{ $order->booking_reference }}</a></th>
                            <td>{{ $order->tour->name }}</td>
                            <td>{{ $order->leadBooker->customer_name }}</td>
                            <td>{{ f_datetime($order->ordered_on) }}</td>
                            <td>{{ f_currency($order->total) }}</td>
                            <td><h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6></td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Member Quotes
                </x-slot:title>
                <table class="table datatable table-striped order-table">
                    <thead>
                    <tr>
                        <th scope="col">Quote Reference</th>
                        <th scope="col">Name</th>
                        <th scope="col">Lead</th>
                        <th scope="col">Expiry Date</th>
                        <th scope="col">Quote Status</th>
                    </tr>
                    </thead>
                    @foreach($organization->memberQuotes as $quote)
                        <tr>
                            <th scope="row"><a href="{{ route('quotes.view', ['quote' => $quote,]) }}">{{ $quote->ref }}</a></th>
                            <td>{{ $quote->name }}</td>
                            <td>{{ $quote->leadTraveller->name }}</td>
                            <td>{{ f_date($quote->expires) }}</td>
                            <td>{{ $quote->status->badge() }}</td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
    </div>
@endsection
