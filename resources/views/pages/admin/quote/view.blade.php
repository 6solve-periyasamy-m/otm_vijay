@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.master')

@section('title', __('quotes.view.title'))

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#schedule-table').DataTable({fixedHeader: true,});
            $('#pricepoint-table').DataTable({fixedHeader: true,});
            $('.summary').DataTable({fixedHeader: true,});
            $('.accommodation').DataTable({fixedHeader: true,});
            $('.activities').DataTable({fixedHeader: true,});
            $('.flights').DataTable({fixedHeader: true,});
            $('.transport').DataTable({fixedHeader: true,});
            $('.extras').DataTable({fixedHeader: true,});
            update(1);
        });
        function getCounterAmount() { let amount = parseInt($('.count-input').val()); return isNaN(amount) || amount < 1 ? 1 : amount; }
        function plus() { update(getCounterAmount()+1); }
        function minus() { let amount = getCounterAmount(); update(amount <= 1 ? 1 : amount-1); }
        function textUpdate() { update(getCounterAmount()); }
        function update(amount) { $('.count-input').val(amount); performRequest(amount); }
        function performRequest(amount) {
            $.get('{{ route('api.quote.cost', ['quote' => $quote,]) }}', {
                '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                '_token': '{{ csrf_token() }}',
                'count': amount,
            }).done(function (xhr, textStatus, errorThrown) {
                if (xhr.success) {
                    updateView(xhr.f_price, xhr.f_total);
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
                        alert('Something went wrong, please try again later');
                }
            });
        }
        function updateView(pricePerPerson, priceTotal) { $('.text-updater').text(priceTotal + " (" + pricePerPerson + ")"); }
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

        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.lead.name') }}</x-slot:title>
            {{ $quote->leadTraveller->name }}
        </x-admin.section.header.detail>

        <x-admin.section.header.detail width="6">
            <x-slot:title>{{ __('quotes.view.lead.contact') }}</x-slot:title>
            <a href="mailto:{{ $quote->leadTraveller->email }}">{{ $quote->leadTraveller->email }}</a>
            (<a href="tel:{{ $quote->leadTraveller->phone }}">{{ $quote->leadTraveller->phone }}</a>)
        </x-admin.section.header.detail>
    </x-admin.section.header>

    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">{{ __('quotes.view.cards.quick.header') }}</h2>
    </div>

    <x-admin.section.card>
        <div class="row">
            <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                <div class="otm-card">
                    <p>{{ __('quotes.view.cards.quick.calculator.header')  }}</p>
                    <h6 class="fw-bold">{{ __('quotes.view.cards.quick.calculator.description')  }}</h6>
                    <p>{{ __('quotes.view.cards.quick.calculator.cost')  }}</p>
                    <h6 class="fw-bold text-updater">Not Calculated Yet</h6>
                    <p>{{ __('quotes.view.cards.quick.calculator.count') }}</p>
                    <h6 class="fw-bold row">
                        <div class="col-12 col-xl-3">
                            <a href="javascript:plus()" class="btn btn-outline-primary btn-sm mb-1">
                                <i class="icon-plus"></i>
                            </a>
                        </div>
                        <div class="col-12 col-xl-6">
                            <x-admin.input name="count" value="1" onchange="textUpdate()" nofloat></x-admin.input>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="javascript:minus()" class="btn btn-outline-primary btn-sm mb-1">
                                <i class="icon-minus"></i>
                            </a>
                        </div>
                    </h6>
                </div>
            </div>
        </div>
    </x-admin.section.card>

    <div class="row">
        <div class="col-xl-12">
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
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
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
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($quote->repository->getComponents() as $componentRepository)
                                <tr>
                                    <td>
                                        {{ $componentRepository->getTourComponent()->getComponentString() }}
                                    </td>
                                    <td>
                                        @if ($componentRepository->getTourComponent()->getComponentString() == 'extra')
                                            {{ __('quotes.view.cards.components.common.na') }}
                                        @else
                                            {{ f_datetime($componentRepository->getTourComponent()->getInventory()->getStartTime()) }}
                                             to
                                            {{ f_datetime($componentRepository->getTourComponent()->getInventory()->getEndTime()) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $componentRepository->getTourComponent()->__toString() }}
                                    </td>
                                    <td>
                                        {{ f_currency($componentRepository->getPurchasePrice()) }}
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
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($quote->accommodation()->with('tourComponent', 'tourComponent.inventory')->get() as $component)
                                <tr>
                                    <td>
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getStartTime()) }}
                                         to
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getEndTime()) }}
                                    </td>
                                    <td>
                                        {{ $component->repository->getTourComponent()->__toString() }}
                                    </td>
                                    <td>
                                        {{ f_currency($component->repository->getPurchasePrice()) }}
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
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($quote->activities()->with('tourComponent', 'tourComponent.inventory')->get() as $component)
                                <tr>
                                    <td>
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getStartTime()) }}
                                         to
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getEndTime()) }}
                                    </td>
                                    <td>
                                        {{ $component->repository->getTourComponent()->__toString() }}
                                    </td>
                                    <td>
                                        {{ f_currency($component->repository->getPurchasePrice()) }}
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
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($quote->flights()->with('tourComponent', 'tourComponent.inventory')->get() as $component)
                                <tr>
                                    <td>
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getStartTime()) }}
                                         to
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getEndTime()) }}
                                    </td>
                                    <td>
                                        {{ $component->repository->getTourComponent()->__toString() }}
                                    </td>
                                    <td>
                                        {{ f_currency($component->repository->getPurchasePrice()) }}
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
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($quote->transport()->with('tourComponent', 'tourComponent.inventory')->get() as $component)
                                <tr>
                                    <td>
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getStartTime()) }}
                                         to
                                        {{ f_datetime($component->repository->getTourComponent()->getInventory()->getEndTime()) }}
                                    </td>
                                    <td>
                                        {{ $component->repository->getTourComponent()->__toString() }}
                                    </td>
                                    <td>
                                        {{ f_currency($component->repository->getPurchasePrice()) }}
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
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($quote->merchandise()->with('tourComponent')->get() as $component)
                                <tr>
                                    <td>
                                        {{ $component->repository->getTourComponent()->__toString() }}
                                    </td>
                                    <td>
                                        {{ f_currency($component->repository->getPurchasePrice()) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
            </x-admin.section.card>
        </div>
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
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:header>{{ __('quotes.view.cards.price-points.header') }}</x-slot:header>
                <form class="form-group row pricepoint-create" action="{{ route('quotes.price-points.store', ['quote' => $quote]) }}" method="post">
                    @csrf
                    <x-admin.input name="quantity" width="5">{{ __('quotes.view.cards.price-points.form.quantity') }}</x-admin.input>
                    <x-admin.input name="cost" width="5">{{ __('quotes.view.cards.price-points.form.cost') }}</x-admin.input>
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
                                <form class="pricepoint-{{$pricePoint->id}}" action="{{ route('quotes.price-points.update', ['quote' => $quote, 'pricePoint' => $pricePoint,]) }}" method="post">
                                    @csrf
                                    <td data-search="{{$pricePoint->quantity}}" data-order="{{$pricePoint->quantity}}">
                                        <x-admin.input name="quantity" value="{{ $pricePoint->quantity }}" nofloat></x-admin.input>
                                    </td>
                                    <td data-search="{{$pricePoint->price_per_person}}" data-order="{{$pricePoint->price_per_person}}">
                                        <x-admin.input name="cost" value="{{ $pricePoint->price_per_person }}" nofloat></x-admin.input>
                                    </td>
                                    <td>
                                        <a href="javascript:$('.pricepoint-{{$pricePoint->id}}').submit()" class="btn btn-outline-success btn-sm mb-1">
                                            <i class="icon-note"></i>
                                        </a>
                                        <a href="javascript:$('#pricepoint-{{ $pricePoint->id }}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </td>
                                </form>
                                <form id="pricepoint-{{ $pricePoint->id }}-delete" class="d-none" method="post" action="{{ route('quotes.price-points.delete', ['quote' => $quote, 'pricePoint' => $pricePoint,]) }}">@csrf</form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-admin.section.card>
        </div>
    </div>
@endsection
