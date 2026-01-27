<div>
    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">Travellers</h2>
    </div>

    <x-admin.section.card>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <button onclick="openModal('admin.quote.prospect.form', {'quote': {{$quote->id}},})" class="btn btn-success">
                    {{Icon::create()}} Add Traveller
                </button>
            </div>
            @foreach($quote->travellers as $traveller)
                <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                    <div class="otm-card">
                        <div class="row">
                            <div class="col-12 font-bold">
                                {{ $traveller->is_lead ? "Lead Traveller" : "Traveller" }}
                            </div>
                            <div class="col-8">
                                {{ $traveller->customer->full_name }}
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-warning mb-0 p-1" onclick="openModal('admin.quote.prospect.form', {'prospect': {{ $traveller->id }},})">
                                    {{ Icon::edit() }}
                                </button>
                                @if($traveller->is_lead)
                                    <span class="btn btn-outline-dark mb-0 p-1" title="Cannot delete lead traveller">
                                        {{ Icon::delete() }}
                                    </span>
                                @else
                                    <a class="btn btn-outline-danger mb-0 p-1" href="{{ route('quotes.prospect.delete', ['quote' => $quote, 'prospect' => $traveller,]) }}">
                                        {{ Icon::delete() }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-5">
                                Travelling
                            </div>
                            <div class="col-1">
                                {{ $traveller->travelling ? Icon::check() : Icon::cross() }}
                            </div>
                            <div class="col-4">
                                Paying
                            </div>
                            <div class="col-1">
                                {{ $traveller->paying ? Icon::check() : Icon::cross() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-admin.section.card>

    <div class="heading pt-2 pb-md-3 pb-2">
        <h2 class="fw-bold">{{ __('quotes.view.cards.quick.header') }}</h2>
    </div>

    <div class="row">
        {{-- Traveller Changer --}}
        <x-admin.section.card width="6">
            <x-slot:title>{{ __('quotes.view.cards.quick.calculator.header') }}</x-slot:title>
            @php
                $string = "Lead is ";
                if ($quote->leadTraveller->travelling) { $string .= "Travelling"; } else { $string .= "Not Travelling"; }
                $string .= " and ";
                if ($quote->leadTraveller->paying) { $string .= "Paying"; } else { $string .= "Not Paying"; }
            @endphp
            <span>{{ $string }}</span>
            <div class="row">
                <h4 class="fw-bold">{{ __('quotes.view.cards.quick.calculator.count') }}</h4>
                <div class="col-12 col-xl-5 gx-2 row">
                    <div class="col-12 text-center">
                        <p>{{ __('quotes.view.cards.quick.calculator.paying') }}</p>
                    </div>
                    <div class="col-12 col-xl-3">
                        <button wire:click="incrementPaying(-1)" class="btn btn-outline-danger btn-sm mb-1">
                            {{ Icon::minus() }}
                        </button>
                    </div>
                    <div class="col-12 col-xl-5">
                        <x-livewire.input name="paying" wire:model="paying" nofloat></x-livewire.input>
                    </div>
                    <div class="col-12 col-xl-3">
                        <button wire:click="incrementPaying(1)" class="btn btn-outline-success btn-sm mb-1">
                            {{ Icon::plus() }}
                        </button>
                    </div>
                </div>
                @if(flag('non-paying.travellers.enabled', true) || $travelling > 0)
                    <div class="col-12 col-xl-6 row">
                        <div class="col-12 text-center">
                            <abbr title="Free-of-Charge (FOC) Travellers will be granted components, but will not be charged any fees">{{ __('quotes.view.cards.quick.calculator.travelling') }}</abbr>
                        </div>
                        <div class="col-12 col-xl-3">
                            <button wire:click="incrementTravelling(-1)" class="btn btn-outline-danger btn-sm mb-1">
                                {{ Icon::minus() }}
                            </button>
                        </div>
                        <div class="col-12 col-xl-6">
                            <x-livewire.input name="travelling" wire:model="travelling" nofloat></x-livewire.input>
                        </div>
                        <div class="col-12 col-xl-3">
                            <button wire:click="incrementTravelling(1)" class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::plus() }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </x-admin.section.card>
        {{-- Actions --}}
        <x-admin.section.card width="6">
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
                <button class="btn btn-info" wire:click="preview">
                    {{ Icon::view() }}
                    {{ __('quotes.view.cards.quick.calculator.preview') }}
                </button>
                {{-- <button onclick="confirmAndSend()" class="btn btn-success">
                    {{ Icon::email() }}
                    {{ __('quotes.view.cards.quick.calculator.send') }}
                </button> --}}
                <button wire:click="openEmailModal" class="btn btn-success" wire:loading.attr="disabled" wire:target="openEmailModal">
                    <span wire:loading.remove wire:target="openEmailModal">{{ Icon::email() }} {{ __('quotes.view.cards.quick.calculator.send') }} </span>
                    <span wire:loading wire:target="openEmailModal">Preparing...</span>
                </button>
                <button wire:click="convert" class="btn btn-warning">
                    {{ Icon::convert() }}
                    {{ __('quotes.view.cards.quick.calculator.convert') }}
                </button>
                @can('costing', \App\Models\Quote\Quote::class)
                    <button wire:click="costs" class="btn btn-secondary">
                        {{ Icon::wallet() }}
                        {{ __('quotes.view.cards.quick.calculator.costing') }}
                    </button>
                @endcan                
            </x-admin.section.otm-text>
        </x-admin.section.card>
        {{-- Component Costs --}}
        <x-admin.section.card width="3">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col" class="fw-bold py-1">Type</th>
                    <th scope="col" class="fw-bold py-1">Amount</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.accommodation') }}<span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></th>
                    <td class="py-1">{{ f_currency($quote->repository->getAccommodationCost(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.activities') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getActivityCost(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.flights') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getFlightCost(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.transport') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getTransportCost(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.merchandise') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getMerchandiseCost(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.section') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getSectionCost(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.per-customer') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getPerCustomerAdditionals(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                <tr>
                    <th scope="row" class="py-1">{{ __('quotes.view.cards.quick.calculator.components.overall') }}</th>
                    <td class="py-1">{{ f_currency($quote->repository->getWholeOrderAdditionals(($quote->leadTraveller->travelling) + $paying + $travelling)) }}</td>
                </tr>
                </tbody>
            </table>
        </x-admin.section.card>
        {{-- Quote Costs --}}
        <x-admin.section.card width="9">
            <div class="row">
                <div class="col-6">
                    <x-admin.section.otm-text class="ctc-updater">
                        <x-slot:header>{{ __('quotes.view.cards.quick.calculator.ctc') }} <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></x-slot:header>
                        {{ f_currency($costToCompany / $fromRate, Settings::currency(), $toRate, $quote->currency) }}
                    </x-admin.section.otm-text>
                </div>
                <div class="col-6 row">
                    <div class="col-4">
                        <button class="btn btn-info" wire:click="calculate">{{ Icon::refresh() }} Refresh Data</button>
                    </div>
                    @if($quote->currency !== null)
                        @if(!($adjust ?? false))
                            <div class="col-8 row">
                                <x-livewire.input width="4" wire:model="fromRate" label="From {{$quote->currency?->code}}" disabled />
                                <x-livewire.input width="4" wire:model="toRate" label="To {{$quote->currency?->code}}" disabled />
                                <div class="col-4">
                                    <button class="btn btn-warning" wire:click="enableEditing">Adjust</button>
                                </div>
                            </div>
                        @else
                            <div class="col-8 row">
                                <x-livewire.input width="4" wire:model="fromRate" label="From {{$quote->currency?->code}}" />
                                <x-livewire.input width="4" wire:model="toRate" label="To {{$quote->currency?->code}}" />
                                <div class="col-4">
                                    <button class="btn btn-success" wire:click="saveConversion">Save</button>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-6">
                            <x-admin.section.otm-text class="profit-updater">
                                <x-slot:header>{{ __('quotes.view.cards.quick.calculator.profit') }} <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></x-slot:header>
                                @if($profit == null)
                                    Conversion Rate Not Set
                                @else
                                    {{ f_currency($profit / $fromRate, Settings::currency(), $toRate, $quote->currency) }}
                                @endif
                            </x-admin.section.otm-text>
                        </div>
                        @if($taxes !== null)
                            <div class="col-6">
                                <x-admin.section.otm-text class="cost-updater">
                                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.taxes') }}</x-slot:header>
                                    {{ f_currency($taxes, Settings::currency(), $toRate, $quote->currency) }}
                                </x-admin.section.otm-text>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-6">
                    <x-livewire.input wire:model="markup" wire:change="calculate" label="Expected Markup" append="%"/>
                </div>
                <div class="col-6">
                    <div class="row">
                        @if($total !== $toBePaid)
                            <div class="col-3">
                                <x-admin.section.otm-text class="cost-updater">
                                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.cost') }}</x-slot:header>
                                    {{ f_currency($total, Settings::currency(), $toRate, $quote->currency) }}
                                </x-admin.section.otm-text>
                            </div>
                        @endif
                        @if($commission !== null)
                            <div class="col-3">
                                <x-admin.section.otm-text class="cost-updater">
                                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.commission') }}</x-slot:header>
                                    {{ f_currency($commission, Settings::currency(), $toRate, $quote->currency) }}
                                </x-admin.section.otm-text>
                            </div>
                        @endif
                        <div class="col-3">
                            <x-admin.section.otm-text class="cost-updater">
                                <x-slot:header>{{ __('quotes.view.cards.quick.calculator.final') }}</x-slot:header>
                                {{ f_currency($toBePaid, Settings::currency(), $toRate, $quote->currency) }}
                            </x-admin.section.otm-text>
                        </div>
                    </div>
                </div>
                <div class="col-6 row">
                    <x-livewire.input wire:model.debounce.300ms="marked_up_price" key="marked_up_price" label="Price per Person ({{ $quote->currency?->code ?? Settings::currency()?->code }})" width="8" />
                    <div class="col-4">
                        <label></label>
                        <button class="btn btn-warning" wire:click="updatePricePoint(true)" title="Update the price point for a single traveller, and update the others to have the same percentage difference">Save Changes</button>
                    </div>
                </div>
            </div>

            <livewire:admin.quote.send-popup-mail :quote="$quote" />
            
        </x-admin.section.card>
        @php
            $isEmpty = $quote->sentQuotes->isEmpty();
            $message = ($isEmpty) ? "Are you sure you want to send email?" : "Are you sure you want to resend email?";
        @endphp
        <script type="text/javascript">
            function confirmAndSend() {
                if (confirm("{{ $message }}")) {
                    Livewire.emit('sendEmail');
                }
            }
        </script>
    </div>
</div>
