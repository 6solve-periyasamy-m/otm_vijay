@extends('layout.master')

@php
/**
 * @var \App\Models\Quote\Quote[] $quotes
 */
@endphp

@section('title', 'All Quotes')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#quote').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    @can('create', \App\Models\Quote\Quote::class)
        <div class="card">
            <div class="card-body">
                <a class="btn btn-success float-end" href="{{ route('quotes.create') }}">
                    <i class="icon-plus"></i>
                    <span>Create New</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px;" href="{{ route('quotes.all', ['historic' => !($historic ?? true),]) }}">
                    <i class="icon-eye"></i>
                    <span>{{ !($historic ?? true) ? "Show" : "Hide" }} Historic (Older than {{ setting('system.historic', 6) }} month(s))</span>
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <table id="quote" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">{{ __('quotes.table.reference') }}</th>
                    <th scope="col">{{ __('quotes.table.package') }}</th>
                    <th scope="col">{{ __('quotes.table.description') }}</th>
                    <th scope="col">{{ __('quotes.table.lead') }}</th>
                    <th scope="col">{{ __('quotes.table.email') }}</th>
                    <th scope="col">{{ __('quotes.table.expiry') }}</th>
                    <th scope="col">{{ __('quotes.table.status') }}</th>
                    <th scope="col">{{ __('custom.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quotes as $quote)
                    <tr>
                        <td><a href="{{ route('quotes.view', ['quote' => $quote,]) }}">{{ $quote->ref }}</a></td>
                        <td>{{ $quote->name }}</td>
                        <td>{{ $quote->description }}</td>
                        <td>{{ $quote->leadTraveller->name }}</td>
                        <td>{{ $quote->leadTraveller->email }}</td>
                        <td>{{ f_date($quote->expires)}}</td>
                        <td>{{ $quote->status->badge() }}</td>
                        <td class="actions">
                            @can('update', \App\Models\Quote\Quote::class)
                                <a href="{{route('quotes.edit', ['quote' => $quote,])}}" class="btn btn-sm btn-outline-success mb-1">
                                    <i class="icon-note"></i>
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                            @endcan
                            @can('delete', \App\Models\Quote\Quote::class)
                                <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                                   onclick="event.preventDefault();document.getElementById('quote-{{ $quote->id }}-delete').submit();">
                                    <i class="icon-trash"></i>
                                </a>
                                <form id="quote-{{ $quote->id }}-delete" action="{{ route('quotes.delete', ['quote' => $quote,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-trash"></i>
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
