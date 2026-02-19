<div class="row">
    @php /** @var \App\Models\Quote\Quote $quote */ @endphp
    {{-- <div class="col-lg-9 mb-3"> --}}
        <div class="main-section mb-3 pr-1" style="padding-right:9px !important;">

            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <div class="text-white rounded-4 shadow p-3 h-100" style="background-color: #295f92;">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.reference') }}</x-slot:title>
                            {{ $quote->ref }}
                        </x-admin.section.header.detail>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white rounded-4 shadow p-3 h-100" style="background-color: #295f92">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.lead.name') }}</x-slot:title>
                            {{ $quote->leadTraveller?->name ?? 'Lead Traveller Not Set' }}
                        </x-admin.section.header.detail>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white rounded-4 shadow p-3 h-100" style="background-color: #295f92">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.consultant') }}</x-slot:title>
                            @if($quote->consultant)
                            {{ $quote->consultant->name }}
                            @else
                            No Consultant Name
                            @endif
                        </x-admin.section.header.detail>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white rounded-4 shadow p-3 h-100" style="background-color: #295f92">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.status') }}</x-slot:title>
                            {{ $quote->status->badge() }}
                        </x-admin.section.header.detail>
                    </div>
                </div>
            </div>


            <div class="row g-3 mb-3 d-flex  flex-colum">
                <div class="col-md-6">
                    <div class="rounded-3 shadow p-3 h-100 justify-content-between" style="background-color: #A3CAEE">

                        <div class="row g-3">

                            <div class="col-md-11 extraclass">
                                <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">{{ __('quotes.view.name') }}:</span>
                                        <x-livewire.input wire:model="quote.name" value="{{ $quote->name }}"
                                            class="rounded-2 flex-grow-1 p-1" />
                                    </div>
                                </x-admin.section.header.detail>
                            </div>

                            <div class="col-md-6 d-flex flex-wrap align-items-baseline text-break" style="width:100%">
                                <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">{{ __('quotes.view.starts') }}:</span>
                                        <span>{{ f_date($quote->date_from) }} → {{ f_date($quote->date_to) }}</span>
                                    </div>
                                </x-admin.section.header.detail>
                                <x-admin.section.header.detail>
                                    <x-slot:title></x-slot:title>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="fw-normal">{{ __('quotes.view.expires') }}:</span>
                                        {{ f_date($quote->expires) }}
                                    </div>
                                </x-admin.section.header.detail>
                                <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal text-nowrap">{{ __('quotes.view.organization') }}:</span>
                                        <span>
                                            @if($quote->organization)
                                            {{ $quote->organization->name }}
                                            @else
                                            -
                                            @endif
                                        </span>
                                    </div>
                                </x-admin.section.header.detail>
                                <x-admin.section.header.detail>
                                    <x-slot:title></x-slot:title>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="fw-normal">{{ __('quotes.view.agent') }}:</span>
                                        {{ $quote->agent?->first_name }} {{ $quote->agent?->last_name ?? '' }}
                                    </div>
                                </x-admin.section.header.detail>
                            </div>

                            <x-admin.section.header.detail>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <x-slot:title></x-slot:title>
                                    <span class="fw-normal">{{ __('quotes.view.lead.contact') }}:</span>
                                    <span class="">
                                        <a href="mailto:{{ $quote->leadTraveller?->email }}">
                                            {{ $quote->leadTraveller?->email }}
                                        </a>
                                        @if($quote->leadTraveller?->phone)
                                        (
                                        <span class="">
                                            <a href="tel:{{ $quote->leadTraveller->phone }}">
                                                {{ $quote->leadTraveller->phone }}
                                            </a>
                                        </span>
                                        )

                                        @endif

                                    </span>
                                </div>
                            </x-admin.section.header.detail>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <x-admin.section.header.detail>
                                    <x-slot:title>{{ __('quotes.view.notes.internal') }}:</x-slot:title>
                                    {{ $quote->internal_notes ?? 'No Internal Notes' }}
                                </x-admin.section.header.detail>
                            </div>
                            <div class="col-md-6">
                                <x-admin.section.header.detail>
                                    <x-slot:title>{{ __('quotes.view.notes.external') }}:</x-slot:title>
                                    {{ $quote->external_notes ?? 'No External Notes' }}
                                </x-admin.section.header.detail>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    @php
                    // Match Calculator.php logic for all calculations
                    $paying = max($quote->paying_count, (int)($quote->paying ?? 0));
                    $travelling = max($quote->travelling_count, (int)($quote->travelling ?? 0));
                    $leadPaying = $quote->leadTraveller?->paying ? 1 : 0;
                    $leadTravelling = $quote->leadTraveller?->travelling ? 1 : 0;
                    $totalTravellerCount = $paying + $travelling + $leadTravelling;
                    $costToCompany = $quote->repository->getTotalCostToCompany($totalTravellerCount);
                    $payingTotal = $paying + $leadPaying;
                    $pricePerPerson = $quote->repository->getPricePerPerson($payingTotal)?->price_per_person ?? 0;
                    $total = $pricePerPerson * $payingTotal;
                    $commission = $quote->commission ? sigfig($total * ($quote->commission / 100)) : 0;
                    $toBePaid = $total - ($commission ?? 0.0);
                    $toRate = $quote->repository->getToRate();
                    if ($quote->taxBracket()?->rate !== null) {
                        $taxes = sigfig($quote->taxBracket()?->calculate($total));
                    } else {
                        $taxes = null;
                    }
                    if ($quote->currency !== null && $quote->currency_id !== Settings::currency()?->id) {
                        if ($quote->from_rate === null) {
                            $profit = null;
                        } else {
                            $profit = sigfig(sigfig($total * $quote->from_rate) - $costToCompany);
                        }
                    } else {
                        $profit = sigfig($total - $costToCompany);
                    }
                    $profit = $profit === null ? null : ($profit - $commission);
                    @endphp
                    <div class="rounded-3 shadow p-3 h-100 "
                        style="background-color: #A3CAEE;display: flex;flex-direction: column;justify-content: space-between; ">
                        <div class="row g-3 right_extra_class">
                            <div class="col-md-5 d-flex flex-wrap gap-2 mb-3 w-100">
                                <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">
                                            {{-- {{ __('quotes.view.cards.quick.calculator.cost') }} --}}
                                            Order Value:
                                        </span>

                                        {{ f_currency(
                                        $total,
                                        $quote->currency,
                                        $quote->from_rate ?? 1,
                                        Settings::currency()
                                        ) }}
                                    </div>
                                </x-admin.section.header.detail>
                                 <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">{{ __('quotes.view.cards.quick.calculator.ctc') }}:</span>
                                        <span>{{ f_currency($costToCompany, Settings::currency(), $toRate, $quote->currency) }}</span>
                                    </div>
                                </x-admin.section.header.detail>

                                <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>

                                        <span class="fw-normal">
                                           {{ __('quotes.view.cards.quick.calculator.final') }}:
                                        </span>

                                        <span>
                                            {{ f_currency(
                                            $taxes,
                                            $quote->currency,
                                            $quote->from_rate ?? 1,
                                            Settings::currency()
                                            ) }}
                                        </span>
                                    </div>
                                </x-admin.section.header.detail>
                                 <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">
                                            {{ __('quotes.view.cards.quick.calculator.profit') }}:
                                        </span>

                                        <span>
                                            @if ($profit === null)
                                            Conversion Rate Not Set
                                            @else
                                            {{ f_currency(
                                            $profit / ($quote->from_rate ?? 1),
                                            $quote->currency,
                                            $quote->from_rate ?? 1,
                                            Settings::currency()
                                            ) }}
                                            @endif
                                        </span>
                                    </div>
                                </x-admin.section.header.detail>
                                 <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">{{ __('quotes.view.cards.quick.calculator.taxes') }}:</span>
                                        <span>{{ f_currency($toBePaid, Settings::currency(), $toRate, $quote->currency) }}</span>
                                    </div>
                                </x-admin.section.header.detail>
                          
                               
                               
                                <x-admin.section.header.detail>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <x-slot:title></x-slot:title>
                                        <span class="fw-normal">
                                            {{ __('quotes.view.cards.quick.calculator.commission') }}:
                                        </span>
                                        <span>
                                            {{ f_currency(
                                            !empty($quote->commission) ? $commission : 0,
                                            $quote->currency,
                                            $quote->from_rate ?? 1,
                                            Settings::currency()
                                            ) }}
                                        </span>
                                    </div>
                                </x-admin.section.header.detail>
                            </div>
                        </div>

                        <div class="d-flex gap-3 flex-wrap mt-3">
                            <x-admin.section.otm-text style="margin-bottom:unset;">
                                <x-slot:header></x-slot:header>
                                {{-- @if(isset($quote->order))
                                <a href="{{ route('orders.view', ['order' => $quote->order,]) }}"
                                    class="btn btn-warning">
                                    {{ Icon::wallet() }}
                                    View Order
                                </a>
                                @endif --}}

                                <a href="{{ route('quotes.edit', ['quote' => $quote,]) }}"
                                    class="btn btn-warning me-2 mb-2">
                                    {{ Icon::edit() }}
                                    {{ __('quotes.view.buttons.edit') }}
                                </a>
                                <button class="btn btn-info me-2 mb-2" wire:click="preview">
                                    {{ Icon::view() }}
                                    {{ __('quotes.view.cards.quick.calculator.preview') }}
                                </button>
                                <button wire:click="openEmailModal" class="btn btn-success me-2 mb-2"
                                    wire:loading.attr="disabled" wire:target="openEmailModal">
                                    <span wire:loading.remove wire:target="openEmailModal">{{ Icon::email() }}
                                        {{ __('quotes.view.cards.quick.calculator.send') }} </span>
                                    <span wire:loading wire:target="openEmailModal">Preparing...</span>
                                </button>
                                <button wire:click="convert" class="btn btn-warning me-2 mb-2">
                                    {{ Icon::convert() }}
                                    {{ __('quotes.view.cards.quick.calculator.convert') }}
                                </button>
                                @can('costing', \App\Models\Quote\Quote::class)
                                <button wire:click="costs" class="btn btn-secondary me-2 mb-2">
                                    {{ Icon::wallet() }}
                                    {{ __('quotes.view.cards.quick.calculator.costing') }}
                                </button>
                                @endcan
                                <a href="{{ route('quotes.status.close', ['quote' => $quote,]) }}"
                                    class="btn btn-danger me-2 mb-2">
                                    {{ Icon::close() }}
                                    {{ __('quotes.view.buttons.close') }}
                                </a>
                            </x-admin.section.otm-text>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="quote-page cost-calculator col-lg-3 d-flex flex-column" style="padding-left:4px !important;">

            <div class="">
                <style>
                    .main-section {
                        flex: 0 0 85%;
                        max-width: 85%;
                    }

                    .cost-calculator {
                        flex: 0 0 17%;
                        max-width: 15%;
                    }

                    .quote-page .card {
                        background: linear-gradient(90.05deg, #FFFFFF 0.05%, #33B5E5 437%);
                        border: 1.94px solid #A3CAEE;
                        box-shadow: 0px 3.88px 3.88px -2px #67676740;
                        border-radius: 10px;
                        padding: 12px;
                        height:101%;
                    }

                    .title {
                        margin-bottom: unset;
                    }

                    .data {
                        margin-bottom: unset;
                    }

                    .minus-icon div {
                        width: 8px !important;
                        height: 8px !important;
                    }

                    .plus-icon div {
                        width: 8px !important;
                        height: 8px !important;
                    }

                    .clse {
                        flex: 0 0 auto;
                        width: 50%;
                        margin-top: unset;
                    }

                    .extraclass .clse {
                        width: 100%
                    }

                    .right_extra_class .clse,
                    .right_extra_class .col-md-5 .clse {
                        flex: unset !important;
                        width: 49% !important;
                    }
                    @media only screen and (max-width: 1366px) {
                    .quote-page .card{
                        height: 105% !important;
                    }
                    }
                </style>

                <x-admin.section.card>
                    <div class="custom-quote-card">
                        <x-slot:title>Additional Travellers</x-slot:title>

                        <h6 class="fw-bold">Add the number of additional travellers</h6>

                        @php
                        $paying = $quote->paying + ($quote->leadTraveller?->paying ? 1 : 0);
                        $travelling = $quote->travelling + ($quote->leadTraveller?->travelling ? 1 : 0);

                        $string = "Lead is ";
                        if ($quote->leadTraveller->travelling) {
                        $string .= "Travelling";
                        } else {
                        $string .= "Not Travelling";
                        }
                        $string .= " and ";
                        if ($quote->leadTraveller->paying) {
                        $string .= "Paying";
                        } else {
                        $string .= "Not Paying";
                        }
                        @endphp

                        <span>{{ $string }}</span>

                        <div class="row mt-3">


                            <div class="col-12 row gx-2 mb-3">

                                <div class="col-12 text-center">
                                    <p style="margin-bottom: unset;margin-top: 10px;">{{ __('quotes.view.cards.quick.calculator.paying') }}</p>
                                </div>

                                <div class="col-3 text-end">
                                    <button wire:click="incrementPaying(-1)"
                                        class="btn btn-outline-danger btn-sm w-100">
                                        <div class="minus-icon">{{ Icon::minus() }}</div>
                                    </button>
                                </div>

                                <div class="col-6">
                                    <x-livewire.input name="paying" class="text-center" wire:model="paying"
                                        style="height: unset;" nofloat />
                                </div>

                                <div class="col-3" style="text-align:center">
                                    <button wire:click="incrementPaying(1)"
                                        class="btn btn-outline-success btn-sm w-100 ">
                                        <div class="plus-icon"> {{ Icon::plus() }}</div>
                                    </button>
                                </div>

                            </div>


                            @if(flag('non-paying.travellers.enabled', true) || $travelling > 0)
                            <div class="col-12 row gx-2">

                                <div class="col-12 text-center">
                                    <abbr style="text-decoration: none"
                                        title="Free-of-Charge (FOC) Travellers will be granted components, but will not be charged any fees">
                                        {{ __('quotes.view.cards.quick.calculator.travelling') }}
                                    </abbr>
                                </div>

                                <div class="col-3 text-end">
                                    <button wire:click="incrementTravelling(-1)"
                                        class="btn btn-outline-danger btn-sm w-100">
                                        <div class="minus-icon">{{ Icon::minus() }}</div>
                                    </button>
                                </div>

                                <div class="col-6">
                                    <x-livewire.input name="travelling" class="text-center" wire:model="travelling"
                                        nofloat style="height: unset;" />
                                </div>

                                <div class="col-3" style="text-align:center">
                                    <button wire:click="incrementTravelling(1)"
                                        class="btn btn-outline-success btn-sm w-100">
                                        <div class="plus-icon">{{ Icon::plus() }}</div>
                                    </button>
                                </div>

                            </div>
                            @endif

                        </div>
                    </div>

                </x-admin.section.card>

            </div>

        </div>

    </div>