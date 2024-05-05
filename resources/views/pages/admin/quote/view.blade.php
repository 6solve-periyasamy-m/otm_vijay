@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.master')

@section('title', __('quotes.view.title'))

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.sent-quotes').DataTable({fixedHeader: true, order: [[0, 'desc'],]});
            $('.sections').DataTable({fixedHeader: true, order: [[0, 'asc'],]});
        });

        function showInstallmentForm(id = null) {
            openModal('admin.quote.installment.form', {quote: {{$quote->id}}, installment: id})
        }
    </script>
@endsection

@section('content')
    <x-admin.section.header>
        @include('partials.admin.quote.details', ['quote' => $quote])
        <div class="col-12">
            @if(isset($quote->order))
                <a href="{{ route('orders.view', ['order' => $quote->order,]) }}" class="btn btn-warning">
                    {{ Icon::wallet() }}
                    View Order
                </a>
            @endif
            <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}" class="btn btn-warning">
                {{ Icon::edit() }}
                {{ __('quotes.view.buttons.edit') }}
            </a>
            <a href="{{ route('quotes.components.add', ['quote' => $quote,]) }}" class="btn btn-primary">
                {{ Icon::create() }}
                {{ __('quotes.view.buttons.add') }}
            </a>
            @if($quote->status == \App\Models\Helper\QuoteStatus::AWAITING)
                <a href="{{ route('quotes.status.approve', ['quote' => $quote,]) }}" class="btn btn-success">
                    {{ Icon::approve() }}
                    {{ __('quotes.view.buttons.approve') }}
                </a>
                <a href="{{ route('quotes.status.changes', ['quote' => $quote,]) }}" class="btn btn-info">
                    {{ Icon::refresh() }}
                    {{ __('quotes.view.buttons.change') }}
                </a>
            @else
                <button onclick="markSent()" class="btn btn-info">
                    {{ Icon::email() }}
                    {{ __('quotes.view.buttons.sent') }}
                </button>
            @endif
            <a href="{{ route('quotes.status.close', ['quote' => $quote,]) }}" class="btn btn-danger">
                {{ Icon::close() }}
                {{ __('quotes.view.buttons.close') }}
            </a>
        </div>
    </x-admin.section.header>

    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">{{ __('quotes.view.cards.quick.header') }}</h2>
    </div>

    {{-- Calculator --}}
    <livewire:admin.quote.calculator :quote="$quote" />

    <hr class="splitter" />

    {{-- Components--}}
    <x-admin.section.card>
        @include('partials.admin.quote.components', ['quote' => $quote,])
    </x-admin.section.card>

    {{-- Cards --}}
    <div class="row">
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:header>
                    <h4 class="fw-bold">{{ __('quotes.view.cards.installments.header') }}</h4>
                    <div class="d-flex float-end">
                        <button class="btn btn-success" onclick="showInstallmentForm()">
                            {{ Icon::create() }} Create New
                        </button>
                    </div>
                </x-slot:header>
                    <table class="datatable table table-striped" id="schedule-table">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('quotes.view.cards.installments.table.type') }}</th>
                            <th scope="col">{{ __('quotes.view.cards.installments.table.due') }}</th>
                            <th scope="col">{{ __('quotes.view.cards.installments.table.amount') }}</th>
                            <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ __('quotes.view.cards.installments.types.deposit') }}</td>
                            <td data-order="0000-00-00">{{ __('quotes.view.cards.installments.with-order') }}</td>
                            <td>{{ f_currency($quote->getDepositAmount()) }} ({{$quote->getDepositPercentage()}}%)</td>
                            <td>
                                <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </a>
                            </td>
                        </tr>
                        @foreach($quote->installments as $installment)
                            <tr>
                                <td>{{ __('quotes.view.cards.installments.types.installment') }}</td>
                                <td>
                                    {{ f_date($installment->due_on) }}
                                </td>
                                <td>
                                    {{ f_currency($installment->getAmount()) }} ({{$installment->getPercentage()}}%)
                                </td>
                                <td>
                                    <a href="javascript:showInstallmentForm({{$installment->id}})" class="btn btn-outline-success btn-sm mb-1" title="Edit">
                                        {{ Icon::edit() }}
                                    </a>
                                    <a href="javascript:$('#installment-{{ $installment->id }}-delete').submit()"
                                       class="btn btn-outline-danger btn-sm mb-1" title="Delete">
                                        {{ Icon::delete() }}
                                    </a>
                                </td>
                                <form id="installment-{{ $installment->id }}-delete" class="d-none" method="post"
                                      action="{{ route('quotes.installments.delete', ['quote' => $quote, 'installment' => $installment,]) }}">@csrf</form>
                            </tr>
                        @endforeach
                        <tr>
                            <td>{{ __('quotes.view.cards.installments.types.remaining') }}</td>
                            <td data-order="{{$quote->final_payment->format('Y-m-d')}}">{{ f_date($quote->final_payment) }}</td>
                            <td>{{ f_currency($quote->remaining) }} ({{ $quote->getRemainingPercentage() }}%)</td>
                            <td>
                                <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>{{ __('quotes.view.cards.price-points.header') }}</x-slot:title>
                <livewire:admin.quote.price-point.form :quote="$quote" />
                <livewire:admin.quote.price-point.table :quote="$quote->id" />
            </x-admin.section.card>
        </div>
        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>{{ __('quotes.view.cards.sections.header') }}</x-slot:title>
                <div class="pb-3 text-end">
                    <a href="{{ route('quotes.section.show', ['quote' => $quote, ]) }}" class="btn btn-primary text-white mb-1">
                        {{ Icon::show() }}
                        Show All
                    </a>
                    <a href="{{ route('quotes.section.hide', ['quote' => $quote, ]) }}" class="btn btn-info text-white mb-1">
                        {{ Icon::hide() }}
                        Hide All
                    </a>
                    <a href="{{ route('quotes.section.create', ['quote' => $quote, ]) }}" class="btn btn-success text-white mb-1">
                        {{ Icon::create() }}
                        New Section
                    </a>
                </div>
                <table class="table table-striped sections" id="sent-quotes-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.sections.order') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sections.title') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sections.body') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sections.image') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sections.hidden') }}</th>
                        <th scope="col">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->sections as $section)
                        <tr>
                            <td>{{ $section->order }}</td>
                            <td>{{ $section->title }}</td>
                            <td>{!! $section->body !!}</td>
                            <td>{{ f_bool(isset($section->image_url)) }}</td>
                            <td>{{ f_bool($section->hidden) }}</td>
                            <td class="actions">
                                <a href="{{ route('quotes.section.edit', ['quote' => $quote, 'section' => $section,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </a>
                                <a href="javascript:$('#section-{{$section->id}}-delete').submit()" title="Delete" class="btn btn-outline-danger btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </a>
                                <form id="section-{{$section->id}}-delete" class="d-none" method="post" action="{{ route('quotes.section.delete', ['quote' => $quote, 'section' => $section,]) }}">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>{{ __('quotes.view.cards.sent.header') }}</x-slot:title>
                <table class="table table-striped sent-quotes" id="sent-quotes-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.sent.when') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sent.email') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sent.reference') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sent.paying') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.sent.travelling') }}</th>
                        <th scope="col">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->sentQuotes as $sent)
                        <tr>
                            <td data-order="{{ $sent->sent->unix() }}">{{ f_datetime($sent->sent) }}</td>
                            <td>{{ $sent->recipient }}</td>
                            <td>{{ $sent->built->ref }}</td>
                            <td>{{ $sent->paid }}</td>
                            <td>{{ $sent->free }}</td>
                            <td class="actions-3">
                                <a href="{{ route('quotes.sent.view', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-info btn-sm mb-1" title="View">
                                    {{ Icon::view() }}
                                </a>
                                <a href="{{ route('quotes.sent.resend', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1" title="Resend">
                                    {{ Icon::email() }}
                                </a>
                                <a href="{{ route('quotes.sent.rebuild', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-danger btn-sm mb-1" title="Rebuild">
                                    {{ Icon::rebuild() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
    </div>
@endsection
