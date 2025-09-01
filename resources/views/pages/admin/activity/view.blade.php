@php
    /** @var \App\Models\Activity\Activity $activity */
@endphp

@extends('layout.component')

@section('title', 'View Activity')

@section('info')
    <div class="otm-callout">
        <div class="row">
            @if(isset($activity->image_url))
                <div class="col-2">
                    <img src="{{ asset($activity->image_url) }}" class="img-thumbnail image large">
                </div>
            @endif
            <div class="col-{{ isset($activity->image_url) ? 10 : 12 }} row">
                <div class="col-12">
                    <h4 class="fw-bold">{{ $activity->name }}</h4>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Location</p>
                    <h6 class="fw-bold">{{ $activity->address }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Description</p>
                    <h6 class="fw-bold">{!! $activity->description !!}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Currency</p>
                    <h6 class="fw-bold">{{ $activity->currency }}</h6>
                </div>
                <div class="col-12 col-xl-6">
                    <p>Internal Notes</p>
                    <h6 class="fw-bold">{{ $activity->internal_notes }}</h6>
                </div>
                <div class="col-12">
                    @can('self-update', \App\Models\Activity\Activity::class)
                        <a class="btn btn-success" href="{{ route('activities.edit', ['activity' => $activity, ]) }}">
                            {{ Icon::edit() }}
                            <span>Edit Activity</span>
                        </a>
                    @endcan
                    @can('update', \App\Models\Activity\Activity::class)
                        <a class="btn btn-info" title="Duplicate With Inventory" href="{{route('activities.duplicate', ['activity' => $activity,])}}">
                            {{ Icon::copy() }}
                            <span>Duplicate With Inventory</span>
                        </a>
                        <a class="btn btn-info" title="Duplicate Without Inventory" href="{{route('activities.duplicate', ['activity' => $activity, 'inventory' => false,])}}">
                            {{ Icon::copy() }}
                            <span>Duplicate Without Inventory</span>
                        </a>
                    @endcan
                    <a class="btn btn-secondary" href="{{route('activities.manifest.view', ['activity' => $activity,])}}">
                        {{ Icon::list() }}
                        <span>View Manifest</span>
                    </a>
                    @can('delete', \App\Models\Activity\Activity::class)
                        <a href="{{ route('activities.archive', ['activity' => $activity]) }}" title="{{ $activity->archived ? "Restore" : "Archive" }}" class="btn btn-{{ $activity->archived ? "warning" : "danger" }}">
                            {{ Icon::archive() }}
                            <span>{{ $activity->archived ? "Restore" : "Archive" }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('inventory')
        <x-admin.section.card>
            <div class="row">
                <div class="col-5"></div>
                <div class="col-3">
                    @can('update', \App\Models\Activity\ActivityInventory::class)
                        <button onclick="$('#import').submit()" class="btn btn-warning float-end">
                            {{ Icon::excel() }}
                            <span>Import Inventory</span>
                        </button>
                        <form id="import" style="width: 50%;" enctype="multipart/form-data" type="form" method="post" action="{{ route('activities.inventory.import', ['activity' => $activity,]) }}">
                            @csrf
                            <x-livewire.input type="file" required name="import" />
                        </form>
                    @endcan
                </div>
                <div class="col-2">
                    @can('update', \App\Models\Activity\ActivityInventory::class)
                        <a href="{{ route('activities.inventory.export', ['activity' => $activity,]) }}" class="btn btn-success">
                            {{ Icon::excel() }}
                            <span>Export Inventory</span>
                        </a>
                    @endcan
                </div>
                <div class="col-2">
                    @can('self-child-access', [$activity, \App\Models\Activity\ActivityInventory::class])
                        <a href="{{ route('activity-inventories.create', ['activity' => $activity, ]) }}"
                           class="btn btn-primary">
                            {{ Icon::create() }}
                            <span>Add Inventory</span>
                        </a>
                    @endcan
                </div>
            </div>
        </x-admin.section.card>
    <x-admin.section.card>
        <table id="activityInventory" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Ticket Type</th>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">FIT Selectable</th>
                <th scope="col">Stock</th>
                <th scope="col">Contracted Stock</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Internal Notes</th>
                <th scope="col">External Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($activity->activityInventory as $inventory)
                <tr>
                    <td>{{ $inventory->ticketType->name }}</td>
                    <td data-sort="{{$inventory->starts_at?->unix()}}">{{ f_datetime($inventory->starts_at) }}</td>
                    <td data-sort="{{$inventory->ends_at?->unix()}}">{{ f_datetime($inventory->ends_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$inventory->stock - $inventory->used_stock}}/{{ $inventory->stock }}
                        <br/>
                        ({{$inventory->used_stock}} Sold)
                    </td>
                    <td>{{ $inventory->contracted }}</td>
                    <td>{{ f_currency($inventory->purchase_price, $activity->currency) }}</td>
                    <td>{{ f_currency($inventory->sales_price) }}</td>
                    <td>{{ $inventory->internal_notes }}</td>
                    <td>{{ $inventory->external_notes }}</td>
                    <td class="actions-4">
                        @can('read', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.manifest.view', ['activity' => $activity, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-secondary btn-sm mb-1"  title="List">
                                {{ Icon::list() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::list() }}
                            </span>
                        @endcan
                        @can('self-child-access', [$activity, \App\Models\Activity\ActivityInventory::class])
                            <a href="{{route('activity-inventories.duplicate', ['activity' => $activity, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-blue btn-sm mb-1"  title="Copy">
                                {{ Icon::copy() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::copy() }}
                            </span>
                        @endcan
                        @can('self-update', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.edit', ['activity' => $activity, 'inventory' => $inventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1"  title="Edit">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @can('self-delete', \App\Models\Activity\ActivityInventory::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1"  title="Delete"
                               onclick="event.preventDefault();document.getElementById('activityInventory-{{ $inventory->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="activityInventory-{{ $inventory->id }}-delete"
                                  action="{{ route('activity-inventories.delete', ['activity' => $activity, 'inventory' => $inventory,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::delete() }}
                            </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
