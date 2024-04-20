@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.master')

@section('title', __('quotes.view.title'))

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.sent-quotes').DataTable({fixedHeader: true, order: [[0, 'desc'],]});
            $('.sections').DataTable({fixedHeader: true, order: [[0, 'asc'],]});
        });
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
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#summary">
                    {{ Icon::list() }} {{ __('quotes.view.cards.components.tabs.summary') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accommodation">
                    {{ Icon::accommodation() }} {{ __('quotes.view.cards.components.tabs.accommodation') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    {{ Icon::activity() }} {{ __('quotes.view.cards.components.tabs.activities') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    {{ Icon::flight() }} {{ __('quotes.view.cards.components.tabs.flights') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transport">
                    {{ Icon::transport() }} {{ __('quotes.view.cards.components.tabs.transport') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#extras">
                    {{ Icon::merchandise() }} {{ __('quotes.view.cards.components.tabs.extras') }}
                </button>
            </li>
        </ul>
        <div id="tables" class="tab-content otm-tab-content">
            <div id="summary" role="tabpanel" class="tab-pane fade show active">
                <table class="datatable autowidth-off table table-striped summary">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.components.common.type') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->repository->getComponents() as $componentRepository)
                        <tr>
                            <td>
                                {{ ucwords($componentRepository->getComponentType()) }}
                            </td>
                            <td>
                                @if ($componentRepository->getComponentType() == 'merchandise')
                                    {{ __('quotes.view.cards.components.common.na') }}
                                @else
                                    {{ f_datetime($componentRepository->getInventory()->getStartTime()) }}
                                    to
                                    {{ f_datetime($componentRepository->getInventory()->getEndTime()) }}
                                @endif
                            </td>
                            <td>
                                {{ $componentRepository->__toString() }}
                            </td>
                            <td>
                                {{ $componentRepository->getPurchasePrice() !== null ? f_currency($componentRepository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ f_currency($componentRepository->getSalesPrice()) }} {{ $componentRepository->priceShown() ? '(Shown)' : '' }}
                            </td>
                            <td>
                                @can('update', \App\Models\Quote\Quote::class)
                                    <a href="{{$componentRepository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                <a href="{{$componentRepository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    {{ Icon::list() }}
                                </a>
                                <form class="d-none all-{{$componentRepository->getComponentType()}}-{{$componentRepository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $componentRepository->getComponentType(), 'id' => $componentRepository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.all-{{$componentRepository->getComponentType()}}-{{$componentRepository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    {{ Icon::delete() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="accommodation" role="tabpanel" class="tab-pane fade">
                <table class="datatable autowidth-off table table-striped summary">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->accommodation()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                            </td>
                            <td>
                                @can('update', \App\Models\Quote\Quote::class)
                                    <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    {{ Icon::list() }}
                                </a>
                                <form class="d-none accommodation-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.accommodation-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    {{ Icon::delete() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="activities" role="tabpanel" class="tab-pane fade">
                <table class="datatable autowidth-off table table-striped summary">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->activities()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                            </td>
                            <td>
                                @can('update', \App\Models\Quote\Quote::class)
                                    <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    {{ Icon::list() }}
                                </a>
                                <form class="d-none activity-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.activity-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    {{ Icon::delete() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="flights" role="tabpanel" class="tab-pane fade">
                <table class="datatable autowidth-off table table-striped summary">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
<th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->flights()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                            </td>
                            <td>
                                @can('update', \App\Models\Quote\Quote::class)
                                    <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    {{ Icon::list() }}
                                </a>
                                <form class="d-none flight-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.flight-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    {{ Icon::delete() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="transport" role="tabpanel" class="tab-pane fade">
                <table class="datatable autowidth-off table table-striped summary">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
<th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->transport()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ f_datetime($component->repository->getInventory()->getStartTime()) }}
                                to
                                {{ f_datetime($component->repository->getInventory()->getEndTime()) }}
                            </td>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                            </td>
                            <td>
                                @can('update', \App\Models\Quote\Quote::class)
                                    <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    {{ Icon::list() }}
                                </a>
                                <form class="d-none transport-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.transport-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    {{ Icon::delete() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="extras" role="tabpanel" class="tab-pane fade">
                <table class="datatable autowidth-off table table-striped summary">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.price') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.components.common.sales_price') }}</th>
                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->merchandise()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ f_currency($component->repository->getSalesPrice()) }} {{ $component->repository->priceShown() ? '(Shown)' : '' }}
                            </td>
                            <td>
                                @can('update', \App\Models\Quote\Quote::class)
                                    <a href="{{$component->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    {{ Icon::list() }}
                                </a>
                                <form class="d-none merchandise-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.merchandise-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    {{ Icon::delete() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-admin.section.card>

    {{-- Cards --}}
    <div class="row">
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:header>{{ __('quotes.view.cards.installments.header') }}</x-slot:header>
                <form class="form-group row installment-create"
                      action="{{ route('quotes.installments.store', ['quote' => $quote]) }}" method="post">
                    @csrf
                    <x-admin.input type="date" name="due"
                                   width="5">{{ __('quotes.view.cards.installments.form.due') }}</x-admin.input>
                    <x-admin.input name="amount"
                                   width="5">{{ __('quotes.view.cards.installments.form.amount') }}</x-admin.input>
                    <x-admin.button href="javascript:$('.installment-create').submit()" width="2" color="primary">
                        {{ Icon::create() }}
                        <span>{{ __('quotes.view.cards.installments.form.create') }}</span>
                    </x-admin.button>
                </form>
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
                        <td>{{ f_currency($quote->deposit) }}</td>
                        <td>
                            <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}"
                               class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        </td>
                    </tr>
                    @foreach($quote->installments as $installment)
                        <tr>
                            <form class="installment-{{$installment->id}}"
                                  action="{{ route('quotes.installments.update', ['quote' => $quote, 'installment' => $installment,]) }}"
                                  method="post">
                                @csrf
                                <td>{{ __('quotes.view.cards.installments.types.installment') }}</td>
                                <td data-search="{{$installment->due_on->format('Y-m-d')}}"
                                    data-order="{{$installment->due_on->format('Y-m-d')}}">
                                    <x-admin.input name="due" type="date"
                                                   value="{{ $installment->due_on->format('Y-m-d') }}"
                                                   nofloat></x-admin.input>
                                </td>
                                <td data-search="{{$installment->amount}}" data-order="{{$installment->amount}}">
                                    <x-admin.input name="amount" value="{{ $installment->amount }}"
                                                   nofloat></x-admin.input>
                                </td>
                                <td>
                                    <a href="javascript:$('.installment-{{$installment->id}}').submit()"
                                       class="btn btn-outline-success btn-sm mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                    <a href="javascript:$('#installment-{{ $installment->id }}-delete').submit()"
                                       class="btn btn-outline-danger btn-sm mb-1">
                                        {{ Icon::delete() }}
                                    </a>
                                </td>
                            </form>
                            <form id="installment-{{ $installment->id }}-delete" class="d-none" method="post"
                                  action="{{ route('quotes.installments.delete', ['quote' => $quote, 'installment' => $installment,]) }}">@csrf</form>
                        </tr>
                    @endforeach
                    <tr>
                        <td>{{ __('quotes.view.cards.installments.types.remaining') }}</td>
                        <td data-order="{{$quote->final_payment->format('Y-m-d')}}">{{ f_date($quote->final_payment) }}</td>
                        <td>{{ f_currency($quote->remaining) }}</td>
                        <td>
                            <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}"
                               class="btn btn-outline-success btn-sm mb-1">
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
                <x-slot:header>{{ __('quotes.view.cards.price-points.header') }}</x-slot:header>
                <livewire:admin.quote.price-point.form :quote="$quote" />
                <livewire:admin.quote.price-point.table :quote="$quote->id" />
            </x-admin.section.card>
        </div>
        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:header>{{ __('quotes.view.cards.sections.header') }}</x-slot:header>
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
                                   class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                <a href="javascript:$('#section-{{$section->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
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
                <x-slot:header>{{ __('quotes.view.cards.sent.header') }}</x-slot:header>
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
                                   class="btn btn-outline-info btn-sm mb-1">
                                    {{ Icon::view() }}
                                </a>
                                <a href="{{ route('quotes.sent.resend', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::email() }}
                                </a>
                                <a href="{{ route('quotes.sent.rebuild', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-danger btn-sm mb-1">
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
