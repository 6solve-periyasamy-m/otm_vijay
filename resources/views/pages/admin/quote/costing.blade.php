@php
/** 
 * @var \App\Models\Quote\Quote $quote
 * @var int $paying
 * @var int $travelling
 */
$pricePerPerson = $quote->repository->getPricePerPerson($paying)->price_per_person;
$basicCtC = $quote->repository->getCustomerCostToCompany();
$remaining = $pricePerPerson * $paying;
$costOfTour = $quote->repository->getTotalCostToCompany($paying + $travelling);
$profit = $remaining - $costOfTour;
@endphp
@extends('layout.master')

@section('title', "Quote Costing")

@push('footer-stack')
    <script>
        function updateProfit(input) {
            let value = parseFloat($(input).val());
            let ctc = parseFloat($(input).attr('ctc'));
            $('.base-price').text(formatCurrency(ctc * (value/100)));
            $('.base-profit').text(formatCurrency((ctc * (value/100)) - ctc));
        }
        function formatCurrency(number) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: '{{ setting('system.currency', 'GBP') }}',
            })
            return formatter.format(number);
        }
    </script>
@endpush

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $quote->name }} @isset($quote->event)({{$quote->event->name}})@endisset</h4>
            </div>
            <div class="col-12 col-xl-2">
                <p>Quote Status</p>
                {{ $quote->status->badge() }}
            </div>
            <div class="col-12 col-xl-2">
                <p>Paying Travellers</p>
                <h6 class="fw-bold">
                    {{ $paying }}
                </h6>
            </div>
            <div class="col-12 col-xl-2">
                <p>Free Travellers</p>
                <h6 class="fw-bold">
                    {{ $travelling }}
                </h6>
            </div>
            <div class="col-12 col-xl-2">
                <p>Price per Person</p>
                <h6 class="fw-bold">
                    {{ f_currency($pricePerPerson) }}
                </h6>
            </div>
            <div class="col-12 col-xl-2">
                <p>Surcharge</p>
                <h6 class="fw-bold">
                    {{ f_currency($quote->single_occupancy_surcharge) }}
                </h6>
            </div>
            <div class="col-12 col-xl-2">
                <p>Dates</p>
                <h6 class="fw-bold">
                    {{ f_date($quote->date_from) }} to {{ f_date($quote->date_to) }}
                </h6>
            </div>
            <div class="col-12">
                <a class="btn btn-warning" href="{{route('quotes.view', ['quote' => $quote,])}}">
                    {{ Icon::back() }}
                    <span>Back To Quote</span>
                </a>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-12">
            <x-admin.section.card>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td style="width: 20%"></td>
                        <th scope="col" class="fw-bold text-center" style="width: 20%">Cost to Company</th>
                        <th scope="col" class="fw-bold text-center" style="width: 20%">Margin</th>
                        <th scope="col" class="fw-bold text-center" style="width: 20%">Cost to Customer</th>
                        <th scope="col" class="fw-bold text-center" style="width: 20%">Profit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row" style="width: 20%">Costing Details</th>
                        <td class="text-center" style="width: 20%">{{ f_currency($basicCtC) }}</td>
                        <td class="text-center" style="width: 20%">
                            @if ($basicCtC > 0)
                                <input style="width: 5rem;" ctc="{{ $basicCtC }}" value="{{ sigfig((($pricePerPerson-$basicCtC)/$basicCtC)*100) }}" onchange="updateProfit(this)"/>%
                            @else
                                No Cost
                            @endif
                        </td>
                        <td class="text-center base-price" style="width: 20%">{{ f_currency($pricePerPerson) }}</td>
                        <td class="text-center base-profit" style="width: 20%">{{ f_currency($pricePerPerson - $basicCtC) }}</td>
                    </tr>
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-4">
            <x-admin.section.card>
                <div class="card-title">
                    <h4 class="fw-bold">Per-Customer Costs</h4>
                </div>
                <form class="form-group row per_customer-create" action="{{ route('additional-cost.store', ['model' => 'quote', 'id' => $quote->id]) }}" method="post">
                    @csrf
                    <x-admin.input name="name" width="5">Name</x-admin.input>
                    <x-admin.input name="amount" width="5">Amount</x-admin.input>
                    <input type="hidden" name="per_customer" value="1" />
                    <x-admin.button href="javascript:$('.per_customer-create').submit()" width="2" color="primary">
                        {{ Icon::create() }}
                    </x-admin.button>
                </form>
                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->costs()->where('per_customer', '=', '1')->get() as $cost)
                        <tr>
                            <form class="form-group row cost-edit-{{$cost->id}}" action="{{ route('additional-cost.update', [ 'cost' => $cost,]) }}" method="post">
                                @csrf
                                <td data-order="{{ $cost->name }}" data-search="{{ $cost->name }}">
                                    <x-admin.input name="name" value="{{ $cost->name }}">Name</x-admin.input>
                                </td>
                                <td data-order="{{ $cost->amount }}" data-search="{{ $cost->amount }}">
                                    <x-admin.input name="amount" value="{{ $cost->amount }}">Amount</x-admin.input>
                                </td>
                                <input type="hidden" name="per_customer" value="1" />
                            </form>
                            <td>
                                <a href="javascript:$('.cost-edit-{{$cost->id}}').submit()" class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                <a href="javascript:$('#cost-{{$cost->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </a>
                                <form id="cost-{{ $cost->id }}-delete" action="{{ route('additional-cost.delete', ['cost' => $cost,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-4">
            <x-admin.section.card>
                <div class="card-title">
                    <h4 class="fw-bold">Key Financials</h4>
                </div>
                <div class="row">
                    <x-admin.section.otm-text width="4">
                        <x-slot:header>
                            Expected
                        </x-slot:header>
                        {{ f_currency($remaining ?? 0) }}
                    </x-admin.section.otm-text>
                    <x-admin.section.otm-text width="4">
                        <x-slot:header>
                            Cost to Company
                        </x-slot:header>
                        {{ f_currency($costOfTour ?? 0) }}
                    </x-admin.section.otm-text>
                    <x-admin.section.otm-text width="6">
                        <x-slot:header>
                            Profit
                        </x-slot:header>
                        @if(($profit ?? 0) <= 0)
                            <span style="color: red">{{ f_currency($profit ?? 0) }}</span>
                        @else
                            <span style="color: green">{{ f_currency($profit ?? 0) }}</span>
                        @endif
                    </x-admin.section.otm-text>
                </div>
            </x-admin.section.card>
        </div>
        <div class="col-xl-4">
            <x-admin.section.card>
                <div class="card-title">
                    <h4 class="fw-bold">Whole Package Costs</h4>
                </div>
                <form class="form-group row whole-tour-create" action="{{ route('additional-cost.store', ['model' => 'quote', 'id' => $quote->id]) }}" method="post">
                    @csrf
                    <x-admin.input name="name" width="5">Name</x-admin.input>
                    <x-admin.input name="amount" width="5">Amount</x-admin.input>
                    <input type="hidden" name="per_customer" value="0" />
                    <x-admin.button href="javascript:$('.whole-tour-create').submit()" width="2" color="primary">
                        {{ Icon::create() }}
                    </x-admin.button>
                </form>
                <table class="table table-striped datatable">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->costs()->where('per_customer', '=', '0')->get() as $cost)
                        <tr>
                            <form class="form-group row cost-edit-{{$cost->id}}" action="{{ route('additional-cost.update', ['cost' => $cost,]) }}" method="post">
                                @csrf
                                <td data-order="{{ $cost->name }}" data-search="{{ $cost->name }}">
                                    <x-admin.input name="name" value="{{ $cost->name }}">Name</x-admin.input>
                                </td>
                                <td data-order="{{ $cost->amount }}" data-search="{{ $cost->amount }}">
                                    <x-admin.input name="amount" value="{{ $cost->amount }}">Amount</x-admin.input>
                                </td>
                                <input type="hidden" name="per_customer" value="0" />
                            </form>
                            <td>
                                <a href="javascript:$('.cost-edit-{{$cost->id}}').submit()" class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                <a href="javascript:$('#cost-{{$cost->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </a>
                                <form id="cost-{{ $cost->id }}-delete" action="{{ route('additional-cost.delete', ['cost' => $cost,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
    </div>
    <hr class="splitter"/>
    <x-admin.section.card>
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#summary">
                    {{ Icon::list() }} {{ __('tours.costing.view.cards.components.tabs.summary') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accommodation">
                    {{ Icon::accommodation() }} {{ __('tours.costing.view.cards.components.tabs.accommodation') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    {{ Icon::activity() }} {{ __('tours.costing.view.cards.components.tabs.activities') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    {{ Icon::flight() }} {{ __('tours.costing.view.cards.components.tabs.flights') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transport">
                    {{ Icon::transport() }} {{ __('tours.costing.view.cards.components.tabs.transport') }}
                </button>
            </li>
            <li class="nav-item col-6 col-md-2">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#extras">
                    {{ Icon::merchandise() }} {{ __('tours.costing.view.cards.components.tabs.extras') }}
                </button>
            </li>
        </ul>
        <div id="tables" class="tab-content otm-tab-content">
            <div id="summary" role="tabpanel" class="tab-pane fade show active">
                <table class="table table-striped summary datatable">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
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
                                    {{ __('tours.costing.view.cards.components.common.na') }}
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
                                {{ $componentRepository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $componentRepository->getPurchasePrice() !== null ? f_currency($componentRepository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $componentRepository->getCost() !== null ? f_currency($componentRepository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $componentRepository->getMargin() !== null ? $componentRepository->getMargin() . '%' : 'No Cost to Company' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="accommodation" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary datatable">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
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
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="activities" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary datatable">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
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
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="flights" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary datatable">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
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
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="transport" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary datatable">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.dates') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
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
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div id="extras" role="tabpanel" class="tab-pane fade">
                <table class="table table-striped summary datatable">
                    <thead>
                    <tr>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.details') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.component_type') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.purchase') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.tour') }}</th>
                        <th scope="col">{{ __('tours.costing.view.cards.components.common.price.margin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($quote->merchandise()->with('inventory')->get() as $component)
                        <tr>
                            <td>
                                {{ $component->repository->__toString() }}
                            </td>
                            <td>
                                {{ $component->repository->getTourComponentType() }}
                            </td>
                            <td>
                                {{ $component->repository->getPurchasePrice() !== null ? f_currency($component->repository->getPurchasePrice()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getCost() !== null ? f_currency($component->repository->getCost()) : 'Not Set' }}
                            </td>
                            <td>
                                {{ $component->repository->getMargin() !== null ? $component->repository->getMargin() . '%' : 'Not Set' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-admin.section.card>
@endsection
