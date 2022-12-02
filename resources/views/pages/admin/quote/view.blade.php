@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.master')

@section('title', __('quotes.view.title'))

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#schedule-table').DataTable({fixedHeader: true,});
            $('#pricepoint-table').DataTable({fixedHeader: true,});
            $('.summary').DataTable({fixedHeader: true, autoWidth: false,});
            $('.accommodation').DataTable({fixedHeader: true, autoWidth: false,});
            $('.activities').DataTable({fixedHeader: true, autoWidth: false,});
            $('.flights').DataTable({fixedHeader: true, autoWidth: false,});
            $('.transport').DataTable({fixedHeader: true, autoWidth: false,});
            $('.extras').DataTable({fixedHeader: true, autoWidth: false,});
            $('.sent-quotes').DataTable({fixedHeader: true, order: [[0, 'desc'],]});
            $('.sections').DataTable({fixedHeader: true, order: [[0, 'asc'],]});
            update(getPayingAmount(), getTravellingAmount());
        });

        function getPayingAmount() {
            let amount = parseInt($('.paying-input').val());
            return isNaN(amount) || amount < 0 ? 0 : amount;
        }

        function getTravellingAmount() {
            let amount = parseInt($('.travelling-input').val());
            return isNaN(amount) || amount < 0 ? 0 : amount;
        }

        function plusPaying() {
            update(getPayingAmount() + 1, getTravellingAmount());
        }

        function minusPaying() {
            let amount = getPayingAmount();
            update(amount <= 0 ? 0 : amount - 1, getTravellingAmount());
        }

        function plusTravelling() {
            update(getPayingAmount(), getTravellingAmount() + 1);
        }

        function minusTravelling() {
            let amount = getTravellingAmount();
            update(getPayingAmount(), amount <= 0 ? 0 : amount - 1);
        }

        function textUpdate() {
            update(getPayingAmount(), getTravellingAmount());
        }

        function update(paying, travelling) {
            $('.paying-input').val(paying);
            $('.travelling-input').val(travelling);
            performRequest(paying, travelling);
        }

        function performRequest(paying, travelling) {
            $.get('{{ route('api.quote.cost', ['quote' => $quote,]) }}', {
                '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                '_token': '{{ csrf_token() }}',
                'paying': paying,
                'travelling': travelling,
            }).done(function (xhr, textStatus, errorThrown) {
                if (xhr.success) {
                    updateView(xhr.f_price, xhr.f_total, xhr.f_profit, xhr.f_profit_total, xhr.margin, xhr.f_ctc);
                } else {
                    alert(xhr.message);
                }
            }).fail(function (xhr, textStatus, errorThrown) {
                switch (xhr.status) {
                    case 429:
                        alert("You're doing this too quickly! Please wait a second before trying again!")
                        break;
                    case 422:
                        alert("Looks like that isn't a number, please try again!")
                        break;
                    case 403:
                        alert("Looks like that failed, we'll refresh the page for you to try again!")
                        location.reload();
                        break;
                    default:
                        console.log(xhr);
                        alert('Something went wrong, please try again');
                }
            });
        }

        function updateView(pricePerPerson, priceTotal, profitPerPerson, profitTotal, margin, costToCompany) {
            $('.ctc-updater').text(costToCompany);
            $('.cost-updater').text(priceTotal + " (" + pricePerPerson + ")");
            $('.profit-updater').text(profitTotal + " (" + profitPerPerson + ") (" + margin + "%)");
        }
    </script>
@endsection

@section('content')
    <x-admin.section.header>
        @include('partials.admin.quote.details', ['quote' => $quote])
        <div class="col-12">

            @if(isset($quote->order))
                <a href="{{ route('orders.view', ['order' => $quote->order,]) }}" class="btn btn-warning">
                    <i class="icon-credit-card"></i>
                    View Order
                </a>
            @endif
            <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}" class="btn btn-warning">
                <i class="icon-note"></i>
                {{ __('quotes.view.buttons.edit') }}
            </a>
            <a href="{{ route('quotes.components.add', ['quote' => $quote,]) }}" class="btn btn-primary">
                <i class="icon-plus"></i>
                {{ __('quotes.view.buttons.add') }}
            </a>
            @if($quote->status == \App\Models\Helper\QuoteStatus::AWAITING)
                <a href="{{ route('quotes.status.approve', ['quote' => $quote,]) }}" class="btn btn-success">
                    <i class="icon-paper-plane"></i>
                    {{ __('quotes.view.buttons.approve') }}
                </a>
                <a href="{{ route('quotes.status.changes', ['quote' => $quote,]) }}" class="btn btn-info">
                    <i class="icon-refresh"></i>
                    {{ __('quotes.view.buttons.change') }}
                </a>
            @endif
            <a href="{{ route('quotes.status.close', ['quote' => $quote,]) }}" class="btn btn-danger">
                <i class="icon-minus"></i>
                {{ __('quotes.view.buttons.close') }}
            </a>
        </div>
    </x-admin.section.header>

    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">{{ __('quotes.view.cards.quick.header') }}</h2>
    </div>

    {{-- Calculator --}}
    <x-admin.section.card>
        <div class="row">
            <x-admin.section.otm-card>
                <x-admin.section.otm-text>
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.header') }}</x-slot:header>
                    {{ __('quotes.view.cards.quick.calculator.description') }}
                </x-admin.section.otm-text>
                <x-admin.section.otm-text class="row">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.count') }}</x-slot:header>
                    <div class="col-12 col-xl-5 gx-2 row">
                        <div class="col-12 text-center">
                            <p>{{ __('quotes.view.cards.quick.calculator.paying') }}</p>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="javascript:minusPaying()" class="btn btn-outline-danger btn-sm mb-1">
                                <i class="icon-minus"></i>
                            </a>
                        </div>
                        <div class="col-12 col-xl-5">
                            <x-admin.input name="paying" value="{{ $quote->leadTraveller->paying ? 1 : 0 }}" onchange="textUpdate()" nofloat></x-admin.input>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="javascript:plusPaying()" class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6 row">
                        <div class="col-12 text-center">
                            <p>{{ __('quotes.view.cards.quick.calculator.travelling') }}</p>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="javascript:minusTravelling()" class="btn btn-outline-danger btn-sm mb-1">
                                <i class="icon-minus"></i>
                            </a>
                        </div>
                        <div class="col-12 col-xl-6">
                            <x-admin.input name="travelling" value="{{ $quote->leadTraveller->travelling && !$quote->leadTraveller->paying ? 1 : 0 }}" onchange="textUpdate()" nofloat></x-admin.input>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="javascript:plusTravelling()" class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-plus"></i>
                            </a>
                        </div>
                    </div>
                </x-admin.section.otm-text>
            </x-admin.section.otm-card>
            <x-admin.section.otm-card row>
                <x-admin.section.otm-text width="6">
                    <x-slot:header>
                        {{ __('quotes.view.cards.quick.calculator.components.accommodation') }}
                        <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span>
                    </x-slot:header>
                    {{ f_currency($quote->repository->getAccommodationCost()) }}
                </x-admin.section.otm-text>
                <x-admin.section.otm-text width="6">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.activities') }}</x-slot:header>
                    {{ f_currency($quote->repository->getActivityCost()) }}
                </x-admin.section.otm-text>
                <x-admin.section.otm-text width="6">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.flights') }}</x-slot:header>
                    {{ f_currency($quote->repository->getFlightCost()) }}
                </x-admin.section.otm-text>
                <x-admin.section.otm-text width="6">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.transport') }}</x-slot:header>
                    {{ f_currency($quote->repository->getTransportCost()) }}
                </x-admin.section.otm-text>
                <x-admin.section.otm-text width="6">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.merchandise') }}</x-slot:header>
                    {{ f_currency($quote->repository->getMerchandiseCost()) }}
                </x-admin.section.otm-text>
                <x-admin.section.otm-text width="6">
                    <x-slot:header>
                        {{ __('quotes.view.cards.quick.calculator.components.total') }}
                        <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span>
                    </x-slot:header>
                    {{ f_currency($quote->repository->getPurchaseTotal()) }}
                </x-admin.section.otm-text>
            </x-admin.section.otm-card>
            <x-admin.section.otm-card>
                <x-admin.section.otm-text class="ctc-updater">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.ctc') }} <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></x-slot:header>
                    Not Calculated Yet
                </x-admin.section.otm-text>
                <x-admin.section.otm-text class="profit-updater">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.profit') }} <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></x-slot:header>
                    Not Calculated Yet
                </x-admin.section.otm-text>
                <x-admin.section.otm-text class="cost-updater">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.cost') }}</x-slot:header>
                    Not Calculated Yet
                </x-admin.section.otm-text>
            </x-admin.section.otm-card>
            <x-admin.section.otm-card>
                <x-admin.section.otm-text>
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.lead.header') }}</x-slot:header>
                    @if(!$quote->leadTraveller->travelling)
                        {{ __('quotes.view.cards.quick.calculator.lead.organizing') }}
                    @elseif(!$quote->leadTraveller->paying)
                        {{ __('quotes.view.cards.quick.calculator.lead.travelling') }}
                    @else
                        {{ __('quotes.view.cards.quick.calculator.lead.paying') }}
                    @endif
                </x-admin.section.otm-text>
                <x-admin.section.otm-text>
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.convert') }}</x-slot:header>
                    <form class="d-none preview-form" target="_blank" action="{{ route('quotes.preview', ['quote' => $quote,]) }}" method="get">
                        <input type="hidden" name="paying" class="paying-input" value="0">
                        <input type="hidden" name="travelling" class="travelling-input" value="0">
                    </form>
                    <a class="btn btn-info" onclick="event.preventDefault();$('.preview-form').submit();">
                        <i class="icon-magnifier"></i>
                        {{ __('quotes.view.cards.quick.calculator.preview') }}
                    </a>
                    <form class="d-none send-form" action="{{ route('quotes.send', ['quote' => $quote,]) }}" method="post">
                        @csrf
                        <input type="hidden" name="paying" class="paying-input" value="0">
                        <input type="hidden" name="travelling" class="travelling-input" value="0">
                    </form>
                    <a href="javascript:$('.send-form').submit()" class="btn btn-success">
                        <i class="icon-envelope"></i>
                        {{ __('quotes.view.cards.quick.calculator.send') }}
                    </a>
                    <form class="d-none convert-form" action="{{ route('quotes.conversion', ['quote' => $quote,]) }}" method="post">
                        @csrf
                        <input type="hidden" name="paying" class="paying-input" value="0">
                        <input type="hidden" name="travelling" class="travelling-input" value="0">
                    </form>
                    <a href="javascript:$('.convert-form').submit()" class="btn btn-warning">
                        <i class="icon-bag"></i>
                        {{ __('quotes.view.cards.quick.calculator.convert') }}
                    </a>
                    <form class="d-none costing-form" action="{{ route('quotes.costing', ['quote' => $quote,]) }}" method="get">
                        <input type="hidden" name="paying" class="paying-input" value="0">
                        <input type="hidden" name="travelling" class="travelling-input" value="0">
                    </form>
                    <a href="javascript:$('.costing-form').submit()" class="btn btn-secondary">
                        <i class="icon-wallet"></i>
                        {{ __('quotes.view.cards.quick.calculator.costing') }}
                    </a>
                </x-admin.section.otm-text>
            </x-admin.section.otm-card>
        </div>
    </x-admin.section.card>

    {{-- Components--}}
    <x-admin.section.card>
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#summary">
                    <i class="icon-list"></i> {{ __('quotes.view.cards.components.tabs.summary') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accommodation">
                    <i class="icon-home"></i> {{ __('quotes.view.cards.components.tabs.accommodation') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    <i class="icon-settings"></i> {{ __('quotes.view.cards.components.tabs.activities') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    <i class="icon-plane"></i> {{ __('quotes.view.cards.components.tabs.flights') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transport">
                    <i class="icon-directions"></i> {{ __('quotes.view.cards.components.tabs.transport') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#extras">
                    <i class="icon-briefcase"></i> {{ __('quotes.view.cards.components.tabs.extras') }}
                </button>
            </li>
        </ul>
        <div id="tables" class="tab-content otm-tab-content">
            <div id="summary" role="tabpanel" class="tab-pane fade show active">
                <table class="table table-striped summary">
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
                                        <i class="icon-note"></i>
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                                @endcan
                                <a href="{{$componentRepository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="icon-list"></i>
                                </a>
                                <form class="d-none all-{{$componentRepository->getComponentType()}}-{{$componentRepository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $componentRepository->getComponentType(), 'id' => $componentRepository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.all-{{$componentRepository->getComponentType()}}-{{$componentRepository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="accommodation" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary">
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
                                        <i class="icon-note"></i>
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="icon-list"></i>
                                </a>
                                <form class="d-none accommodation-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.accommodation-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="activities" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary">
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
                                        <i class="icon-note"></i>
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="icon-list"></i>
                                </a>
                                <form class="d-none activity-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.activity-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="flights" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary">
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
                                        <i class="icon-note"></i>
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="icon-list"></i>
                                </a>
                                <form class="d-none flight-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.flight-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="transport" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary">
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
                                        <i class="icon-note"></i>
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="icon-list"></i>
                                </a>
                                <form class="d-none transport-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.transport-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="extras" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary">
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
                                        <i class="icon-note"></i>
                                    </a>
                                @else
                                    <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                                @endcan
                                <a href="{{$component->repository->getConvertUrl()}}" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="icon-list"></i>
                                </a>
                                <form class="d-none merchandise-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}"
                                      action="{{ route('quotes.components.delete', ['quote' => $quote, 'type' => $component->repository->getComponentType(), 'id' => $component->repository->get()->id]) }}"
                                      method="post">
                                    @csrf
                                </form>
                                <a href="javascript:$('.merchandise-{{$component->repository->getComponentType()}}-{{$component->repository->get()->id}}').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="icon-trash"></i>
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
                        <i class="icon-plus"></i>
                        <span>{{ __('quotes.view.cards.installments.form.create') }}</span>
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
                    <tr>
                        <td>{{ __('quotes.view.cards.installments.types.deposit') }}</td>
                        <td data-order="0000-00-00">{{ __('quotes.view.cards.installments.with-order') }}</td>
                        <td>{{ f_currency($quote->deposit) }}</td>
                        <td>
                            <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}"
                               class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-note"></i>
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
                                        <i class="icon-note"></i>
                                    </a>
                                    <a href="javascript:$('#installment-{{ $installment->id }}-delete').submit()"
                                       class="btn btn-outline-danger btn-sm mb-1">
                                        <i class="icon-trash"></i>
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
                                <i class="icon-note"></i>
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
                <form class="form-group row pricepoint-create"
                      action="{{ route('quotes.price-points.store', ['quote' => $quote]) }}" method="post">
                    @csrf
                    <x-admin.input name="quantity"
                                   width="5">{{ __('quotes.view.cards.price-points.form.quantity') }}</x-admin.input>
                    <x-admin.input name="cost"
                                   width="5">{{ __('quotes.view.cards.price-points.form.cost') }}</x-admin.input>
                    <x-admin.button href="javascript:$('.pricepoint-create').submit()" width="2" color="primary">
                        <i class="icon-plus"></i>
                        <span>{{ __('quotes.view.cards.price-points.form.create') }}</span>
                    </x-admin.button>
                </form>
                <table class="table table-striped" id="pricepoint-table">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('quotes.view.cards.price-points.table.quantity') }}</th>
                        <th scope="col">{{ __('quotes.view.cards.price-points.table.cost') }}</th>
                        <th scope="col" class="actions">{{ __('custom.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->pricePoints as $pricePoint)
                        <tr>
                            <form class="pricepoint-{{$pricePoint->id}}"
                                  action="{{ route('quotes.price-points.update', ['quote' => $quote, 'pricePoint' => $pricePoint,]) }}"
                                  method="post">
                                @csrf
                                <td data-search="{{$pricePoint->quantity}}" data-order="{{$pricePoint->quantity}}">
                                    <x-admin.input name="quantity" value="{{ $pricePoint->quantity }}"
                                                   nofloat></x-admin.input>
                                </td>
                                <td data-search="{{$pricePoint->price_per_person}}"
                                    data-order="{{$pricePoint->price_per_person}}">
                                    <x-admin.input name="cost" value="{{ $pricePoint->price_per_person }}"
                                                   nofloat></x-admin.input>
                                </td>
                                <td>
                                    <a href="javascript:$('.pricepoint-{{$pricePoint->id}}').submit()"
                                       class="btn btn-outline-success btn-sm mb-1">
                                        <i class="icon-note"></i>
                                    </a>
                                    <a href="javascript:$('#pricepoint-{{ $pricePoint->id }}-delete').submit()"
                                       class="btn btn-outline-danger btn-sm mb-1">
                                        <i class="icon-trash"></i>
                                    </a>
                                </td>
                            </form>
                            <form id="pricepoint-{{ $pricePoint->id }}-delete" class="d-none" method="post"
                                  action="{{ route('quotes.price-points.delete', ['quote' => $quote, 'pricePoint' => $pricePoint,]) }}">@csrf</form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:header>{{ __('quotes.view.cards.sections.header') }}</x-slot:header>
                <div class="pb-3 text-end">
                    <a href="{{ route('quotes.section.show', ['quote' => $quote, ]) }}" class="btn btn-primary text-white mb-1">
                        <i class="icon-key"></i>
                        Show All
                    </a>
                    <a href="{{ route('quotes.section.hide', ['quote' => $quote, ]) }}" class="btn btn-info text-white mb-1">
                        <i class="icon-lock"></i>
                        Hide All
                    </a>
                    <a href="{{ route('quotes.section.create', ['quote' => $quote, ]) }}" class="btn btn-success text-white mb-1">
                        <i class="icon-plus"></i>
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
                                    <i class="icon-note"></i>
                                </a>
                                <a href="javascript:$('#section-{{$section->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
                                    <i class="icon-trash"></i>
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
                                    <i class="icon-magnifier"></i>
                                </a>
                                <a href="{{ route('quotes.sent.resend', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-success btn-sm mb-1">
                                    <i class="icon-envelope"></i>
                                </a>
                                <a href="{{ route('quotes.sent.rebuild', ['quote' => $quote, 'sent' => $sent,]) }}"
                                   class="btn btn-outline-danger btn-sm mb-1">
                                    <i class="icon-layers"></i>
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
