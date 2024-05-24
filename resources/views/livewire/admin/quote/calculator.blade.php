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
            <div class="col-12 col-xl-6 row">
                <div class="col-12 text-center">
                    <p>{{ __('quotes.view.cards.quick.calculator.travelling') }}</p>
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
            <button onclick="confirmAndSend()" class="btn btn-success">
                {{ Icon::email() }}
                {{ __('quotes.view.cards.quick.calculator.send') }}
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
    <x-admin.section.card width="6" class="row">
        <x-admin.section.otm-text width="6">
            <x-slot:header>
                {{ __('quotes.view.cards.quick.calculator.components.accommodation') }}
                <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span>
            </x-slot:header>
            {{ f_currency($quote->repository->getAccommodationCost(1 + $paying + $travelling)) }}
        </x-admin.section.otm-text>
        <x-admin.section.otm-text width="6">
            <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.activities') }}</x-slot:header>
            {{ f_currency($quote->repository->getActivityCost(1 + $paying + $travelling)) }}
        </x-admin.section.otm-text>
        <x-admin.section.otm-text width="6">
            <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.flights') }}</x-slot:header>
            {{ f_currency($quote->repository->getFlightCost(1 + $paying + $travelling)) }}
        </x-admin.section.otm-text>
        <x-admin.section.otm-text width="6">
            <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.transport') }}</x-slot:header>
            {{ f_currency($quote->repository->getTransportCost(1 + $paying + $travelling)) }}
        </x-admin.section.otm-text>
        <x-admin.section.otm-text width="6">
            <x-slot:header>{{ __('quotes.view.cards.quick.calculator.components.merchandise') }}</x-slot:header>
            {{ f_currency($quote->repository->getMerchandiseCost(1 + $paying + $travelling)) }}
        </x-admin.section.otm-text>
        <x-admin.section.otm-text width="6">
            <x-slot:header>
                {{ __('quotes.view.cards.quick.calculator.components.total') }}
                <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span>
            </x-slot:header>
            {{ f_currency($quote->repository->getPurchaseTotal()) }}
        </x-admin.section.otm-text>
    </x-admin.section.card>
    {{-- Quote Costs --}}
    <x-admin.section.card width="6">
        <div class="row">
            <div class="col-6">
                <x-admin.section.otm-text class="ctc-updater">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.ctc') }} <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></x-slot:header>
                    {{ f_currency($costToCompany) }}
                </x-admin.section.otm-text>
            </div>
            <div class="col-6">
                <button class="btn btn-info" wire:click="calculate">Refresh</button>
            </div>
            <div class="col-6">
                <x-admin.section.otm-text class="profit-updater">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.profit') }} <span style="text-decoration-line: underline; text-decoration-style: dotted;" title="{{ __('quotes.view.cards.quick.calculator.components.approximate') }}">*</span></x-slot:header>
                    {{ f_currency($profit) }} ({{ sigfig($margin) }}%)
                </x-admin.section.otm-text>
            </div>
            <div class="col-6">
                <x-livewire.input wire:model="markup" wire:change="calculate" label="Expected Markup" append="%"/>
            </div>
            <div class="col-6">
                <x-admin.section.otm-text class="cost-updater">
                    <x-slot:header>{{ __('quotes.view.cards.quick.calculator.cost') }}</x-slot:header>
                    {{ f_currency($total) }}
                </x-admin.section.otm-text>
            </div>
            <div class="col-6 row">
                <x-livewire.input wire:model.debounce.300ms="marked_up_price" label="Price per Person" width="4" />
                <div class="col-4">
                    <label></label>
                    <button class="btn-warning" wire:click="updatePricePoint()">Update Single Price Point</button>
                </div>
                <div class="col-4">
                    <label></label>
                    <button class="btn-danger" wire:click="updatePricePoint(true)">Update All Price Points</button>
                </div>
            </div>
        </div>
    </x-admin.section.card>
</div>
<script type="text/javascript">
    function confirmAndSend() {
        if (confirm('Are you sure you want to resend email?')) {
            Livewire.emit('sendEmail');
        }
    }
</script>
