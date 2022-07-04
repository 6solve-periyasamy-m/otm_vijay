@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.master')

@section('title', __('quotes.view.title'))

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#schedule-table').DataTable({fixedHeader: true,});
        });
    </script>
@endsection

@section('content')
    <x-admin.section.header>
        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.reference') }}</x-slot:title>
            {{ $quote->reference }}
        </x-admin.section.header.detail>

        <x-admin.section.header.detail width="6" raw>
            <x-slot:title>{{ __('quotes.view.status') }}</x-slot:title>
            {{ $quote->status->badge() }}
        </x-admin.section.header.detail>

        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.name') }}</x-slot:title>
            {{ $quote->tour->name }}
        </x-admin.section.header.detail>

        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.expires') }}</x-slot:title>
            {{ f_date($quote->expires) }}
        </x-admin.section.header.detail>

        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.starts') }}</x-slot:title>
            {{ f_date($quote->tour->date_from) }}
        </x-admin.section.header.detail>

        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.ends') }}</x-slot:title>
            {{ f_date($quote->tour->date_to) }}
        </x-admin.section.header.detail>
    </x-admin.section.header>

    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">{{ __('quotes.view.cards.customers.header') }}</h2>
    </div>

    <x-admin.section.card>
        @foreach($quote->travellers as $traveller)
            <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                <div class="otm-card">
                    <p>{{ ($quote->lead_traveller_id == $traveller->id) ? 'Lead Booker' : ' Additional Traveller'}}</p>
                    <h6 class="fw-bold">
                        <a href="" class="link-info">
                            {{ $traveller->name }}
                        </a>
                    </h6>
                    <p>Email Address</p>
                    <h6 class="fw-bold">{{ $traveller->email }}</h6>
                    <p>Purchase Price of Components</p>
                    <h6 class="fw-bold">{{ f_currency($traveller->repository->getPurchaseTotal()) }}</h6>
                </div>
            </div>
        @endforeach
    </x-admin.section.card>

    <div class="row">
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:header>{{ __('quotes.view.cards.installments.header') }}</x-slot:header>
                <form class="form-group row installment-create" action="{{ route('quotes.installments.store', ['quote' => $quote]) }}" method="post">
                    @csrf
                    <x-admin.input type="date" name="due" width="4">{{ __('quotes.view.cards.installments.form.due') }}</x-admin.input>
                    <x-admin.input name="amount" width="4">{{ __('quotes.view.cards.installments.form.amount') }}</x-admin.input>
                    <x-admin.button href="javascript:$('.installment-create').submit()" width="2" color="primary">
                        <i class="icon-plus"></i>
                        <span>{{ __('quotes.view.cards.installments.form.create') }}</span>
                    </x-admin.button>
                    <x-admin.button href="#" width="2" color="warning">
                        <i class="icon-refresh"></i>
                        <span>{{ __('quotes.view.cards.installments.form.refresh') }}</span>
                    </x-admin.button>
                </form>
                <table class="table table-striped" id="schedule-table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('quotes.view.cards.installments.table.type') }}</th>
                            <th scope="col">{{ __('quotes.view.cards.installments.table.due') }}</th>
                            <th scope="col">{{ __('quotes.view.cards.installments.table.amount') }}</th>
                            <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quote->installments as $installment)
                            <tr>
                                <form class="installment-{{$installment->id}}" action="{{ route('quotes.installments.update', ['quote' => $quote, 'installment' => $installment,]) }}" method="post">
                                    @csrf
                                    <td>{{ __('quotes.view.cards.installments.types.installment') }}</td>
                                    <td data-search="{{$installment->due_on->format('Y-m-d')}}" data-order="{{$installment->due_on->format('Y-m-d')}}">
                                        <x-admin.input name="due" type="date" value="{{ $installment->due_on->format('Y-m-d') }}" nofloat></x-admin.input>
                                    </td>
                                    <td data-search="{{$installment->amount}}" data-order="{{$installment->amount}}">
                                        <x-admin.input name="amount" value="{{ $installment->amount }}" nofloat></x-admin.input>
                                    </td>
                                    <td>
                                        <a href="javascript:$('.installment-{{$installment->id}}').submit()" class="btn btn-outline-success btn-sm mb-1">
                                            <i class="icon-note"></i>
                                        </a>
                                        <a href="javascript:$('#installment-{{ $installment->id }}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </td>
                                </form>
                                <form id="installment-{{ $installment->id }}-delete" class="d-none" method="post" action="{{ route('quotes.installments.delete', ['quote' => $quote, 'installment' => $installment,]) }}">@csrf</form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
    </div>
@endsection
