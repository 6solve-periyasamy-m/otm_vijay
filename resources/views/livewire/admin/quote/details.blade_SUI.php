<style>
.vq_four_col .bg-primary{
    background-color:#295F92 !important;
}
.vq_two_col{
     background-color:#A3CAEE !important;
}
.view_quote_comclass p,
.view_quote_comclass .fw-bold{
    margin-bottom:unset;
}
.vq_calculator_mod .card {
    background: linear-gradient(90.05deg, #FFFFFF 0.05%, #33B5E5 437%);
    border: 1.94px solid #A3CAEE;
    box-shadow: 0px 3.88px 3.88px -2px #67676740;
    border-radius: 10px;
    padding: 8px;
}
.vq_button_div h6{
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.textbox_row .col-12.mb-3 .col-12.col-xl-12 h6.fw-bold{
    display: flex;
    gap: 10px;
}
.quote_name_input{
    width: 100%;
}
.vq_two_col .vq_col_class {
        width: 50%;
    flex: unset;
    padding-bottom:10px;
}
.vq_two_col_right{
    display: flex;
    flex-wrap: wrap;
}
.vq_two_col_right .vq_col_innerclass{
width: 50%;
    flex: unset;
    padding-bottom: 10px;
}
.vq_two_col .col-12.mb-3 .vq_col_class{
width: 92%;
}
.vq_two_col .col-12.mb-3 {
    margin-bottom:unset !important;
}
</style>
<div class="row view_quote_comclass">
    @php /** @var \App\Models\Quote\Quote $quote */ @endphp
    <div class="col-lg-9 mb-3">
        <div class="row g-2 mb-3">
            <div class="col-md-3 vq_four_col">
                <div class="bg-primary text-white rounded-4 shadow p-3 h-100">
                    <x-admin.section.header.detail>
                        <x-slot:title>{{ __('quotes.view.reference') }}</x-slot:title>
                        {{ $quote->ref }}
                    </x-admin.section.header.detail>
                </div>
            </div>
            <div class="col-md-3 vq_four_col">
                <div class="bg-primary text-white rounded-4 shadow p-3 h-100">
                    @isset($quote->event?->name)
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.event') }}</x-slot:title>
                            {{ $quote->event->name }}
                        </x-admin.section.header.detail>
                    @endisset
                </div>
            </div>
            <div class="col-md-3 vq_four_col">
                <div class="bg-primary text-white rounded-4 shadow p-3 h-100">
                    <x-admin.section.header.detail>
                        <x-slot:title>{{ __('quotes.view.expires') }}</x-slot:title>
                        {{ f_date($quote->expires) }}
                    </x-admin.section.header.detail>
                </div>
            </div>
            <div class="col-md-3 vq_four_col">
                <div class="bg-primary text-white rounded-4 shadow p-3 h-100">
                    <x-admin.section.header.detail>
                        <x-slot:title>{{ __('quotes.view.status') }}</x-slot:title>
                        {{ $quote->status->badge() }}
                    </x-admin.section.header.detail>
                </div>
            </div>
        </div>


        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="bg-info-subtle rounded-3 shadow p-3 h-100 vq_two_col">
                    <div class="row textbox_row">
                                        <div class="col-12 mb-3">
                            <x-admin.section.header.detail>
                                <x-slot:title></x-slot:title>{{ __('quotes.view.name') }}
                                <x-livewire.input wire:model="quote.name" value="{{ $quote->name }}"
                                    class="border rounded-4 p-2" />
                            </x-admin.section.header.detail>
                        </div>
                        
                   
                            <x-admin.section.header.detail>
                                <x-slot:title>{{ __('quotes.view.starts') }}</x-slot:title>
                                {{ f_date($quote->date_from) }} to {{ f_date($quote->date_to) }}
                            </x-admin.section.header.detail>
                            <x-admin.section.header.detail>
                                <x-slot:title>{{ __('quotes.view.lead.name') }}</x-slot:title>
                                {{ $quote->leadTraveller?->name ?? 'Lead Traveller Not Set' }}
                            </x-admin.section.header.detail>
                            <x-admin.section.header.detail>
                                <x-slot:title>{{ __('quotes.view.lead.contact') }}</x-slot:title>
                                <a href="mailto:{{ $quote->leadTraveller?->email }}">
                                    {{ $quote->leadTraveller?->email ?? 'No Email Found' }}
                                </a>
                                (<a href="tel:{{ $quote->leadTraveller?->phone ?? 'No Telephone Found' }}">
                                    {{ $quote->leadTraveller?->phone ?? 'No Telephone Found' }}
                                </a>)
                            </x-admin.section.header.detail>
                      
                        <div class="col-md-6">
                            <div class="d-flex gap-2 mb-3">
                                <x-admin.section.header.detail width="3">
                                    <x-slot:title>{{ __('quotes.view.consultant') }}</x-slot:title>
                                    @if($quote->consultant !== null)
                                        {{ $quote->consultant->name }} ({{ $quote->consultant->email }})
                                    @else
                                        No Consultant
                                    @endif
                                </x-admin.section.header.detail>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <x-admin.section.header.detail>
                                <x-slot:title>{{ __('quotes.view.notes.internal') }}</x-slot:title>
                                {{ $quote->internal_notes }}
                            </x-admin.section.header.detail>
                        </div>
                        <div class="col-md-6">
                            <x-admin.section.header.detail>
                                <x-slot:title>{{ __('quotes.view.notes.external') }}</x-slot:title>
                                {{ $quote->external_notes }}
                            </x-admin.section.header.detail>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-info-subtle rounded-3 shadow p-3 h-100 vq_two_col vq_two_col_right">
                    @php
                        $totalTravellerCount = $paying + $travelling;
                        $costToCompany = $quote->repository->getTotalCostToCompany($totalTravellerCount);
                        $pricePerPerson = $quote->repository->getPricePerPerson($paying)?->price_per_person ?? 0;
                        $total = $pricePerPerson * $paying;
                        $commission = $quote->commission ? ($total * ($quote->commission / 100)) : 0;

                        if ($quote->currency !== null && $quote->currency_id !== Settings::currency()?->id) {
                            if ($quote->from_rate === null) {
                                $profit = null;
                            } else {
                                $profit = sigfig(sigfig($total * $quote->from_rate) - $costToCompany);
                            }
                        } else {
                            $profit = sigfig($total - $costToCompany);
                        }
                        if ($quote->taxBracket()?->rate !== null) {
                            $taxes = sigfig($quote->taxBracket()?->calculate($total));
                        } else {
                            $taxes = null;
                        }
                        $profit = $profit === null ? null : ($profit - $commission);
                    @endphp
                    <div class="vq_col_innerclass">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.cards.quick.calculator.profit') }}</x-slot:title>
                            @if($profit === null)
                                Conversion Rate Not Set
                            @else
                                {{ f_currency($profit / ($quote->from_rate ?? 1), $quote->currency, $quote->from_rate ?? 1, Settings::currency()) }}
                            @endif
                        </x-admin.section.header.detail>
                    </div>
                    <div class="vq_col_innerclass">
                        @if($taxes !== null)
                            <x-admin.section.header.detail>
                                <x-slot:title>{{ __('quotes.view.cards.quick.calculator.taxes') }}</x-slot:title>
                                {{ f_currency($taxes, $quote->currency, $quote->from_rate ?? 1, Settings::currency()) }}
                            </x-admin.section.header.detail>
                        @endif
                    </div>
                    <div class="vq_col_innerclass">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.cards.quick.calculator.commission') }}</x-slot:title>
                            @if(!empty($quote->commission))
                                {{ f_currency($commission, $quote->currency, $quote->from_rate ?? 1, Settings::currency()) }}
                            @else
                                {{ f_currency(0, $quote->currency, $quote->from_rate ?? 1, Settings::currency()) }}
                            @endif
                        </x-admin.section.header.detail>
                    </div>
                    <div class="vq_col_innerclass">
                        <x-admin.section.header.detail>
                            <x-slot:title>{{ __('quotes.view.cards.quick.calculator.cost') }}</x-slot:title>
                            {{ f_currency($total, $quote->currency, $quote->from_rate ?? 1, Settings::currency()) }}
                        </x-admin.section.header.detail>
                    </div>
                    <div class="d-flex gap-2 flex-wrap mt-3 vq_button_div">
                        <x-admin.section.otm-text>
                            <x-slot:header></x-slot:header>
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
                            <button class="btn btn-info" wire:click="preview">
                                {{ Icon::view() }}
                                {{ __('quotes.view.cards.quick.calculator.preview') }}
                            </button>
                            <button wire:click="openEmailModal" class="btn btn-success" wire:loading.attr="disabled"
                                wire:target="openEmailModal">
                                <span wire:loading.remove wire:target="openEmailModal">{{ Icon::email() }}
                                    {{ __('quotes.view.cards.quick.calculator.send') }} </span>
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
                            <a href="{{ route('quotes.status.close', ['quote' => $quote,]) }}" class="btn btn-danger">
                                {{ Icon::close() }}
                                {{ __('quotes.view.buttons.close') }}
                            </a>
                        </x-admin.section.otm-text>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="col-lg-3 vq_calculator_mod">
 <livewire:admin.quote.send-popup-mail :quote="$quote" />
        <div>

            <x-admin.section.card>
                <x-slot:title>{{ __('quotes.view.cards.quick.calculator.header') }}</x-slot:title>

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

                <div class="row">

                    <h4 class="fw-bold">{{ __('quotes.view.cards.quick.calculator.count') }}</h4>

                    <div class="col-12 row gx-2 mb-3">

                        <div class="col-12 text-center">
                            <p>{{ __('quotes.view.cards.quick.calculator.paying') }}</p>
                        </div>

                        <div class="col-2 text-end">
                            <button wire:click="incrementPaying(-1)" class="btn btn-outline-danger btn-sm">
                                {{ Icon::minus() }}
                            </button>
                        </div>

                        <div class="col-8">
                            <x-livewire.input name="paying" class="text-center" wire:model="paying" nofloat />
                        </div>



                        <div class="col-2 text-start">
                            <button wire:click="incrementPaying(1)" class="btn btn-outline-success btn-sm">
                                {{ Icon::plus() }}
                            </button>
                        </div>

                    </div>

                    @if(flag('non-paying.travellers.enabled', true) || $travelling > 0)
                        <div class="col-12 row gx-2">

                            <div class="col-12 text-center">
                                <abbr
                                    title="Free-of-Charge (FOC) Travellers will be granted components, but will not be charged any fees">
                                    {{ __('quotes.view.cards.quick.calculator.travelling') }}
                                </abbr>
                            </div>

                            <div class="col-2 text-end">
                                <button wire:click="incrementTravelling(-1)" class="btn btn-outline-danger btn-sm">
                                    {{ Icon::minus() }}
                                </button>
                            </div>

                            <div class="col-8">
                                <x-livewire.input name="travelling" class="text-center" wire:model="travelling" nofloat />
                            </div>

                            <div class="col-2 text-start">
                                <button wire:click="incrementTravelling(1)" class="btn btn-outline-success btn-sm">
                                    {{ Icon::plus() }}
                                </button>
                            </div>

                        </div>
                    @endif

                </div>
            </x-admin.section.card>
        </div>

    </div>

</div>