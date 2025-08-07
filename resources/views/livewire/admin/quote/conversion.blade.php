<div>
    <x-admin.section.card>
        <x-slot:title>Travellers</x-slot:title>
        <div class="row">
            @foreach($travellers as $key => $traveller)
                @if ($traveller['id'] === -1)
                    <div class="col-6 row">
                        <x-livewire.input disabled label="Lead Traveller" alert-changes width="10" value="{{$traveller['name']}}" />
                        <div class="col-2 my-auto">
                            <div class="btn btn-{{ $traveller['paying'] ? 'success' : 'danger' }}">
                                {{ $traveller['paying'] ? 'Paying' : 'Not Paying' }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-6 row">
                        <x-livewire.input.select.customer clear="true" placeholder="Unknown Traveller" label="Traveller {{ $key }}" value="{{ $traveller['id'] ?? null }}" alert-changes name="travellers.{{$key}}.id" inlineJs="@this.refreshCustomers()" width="10" />
                        <div class="col-2 my-auto">
                            <div class="btn btn-{{ $traveller['paying'] ? 'success' : 'danger' }}">
                                {{ $traveller['paying'] ? 'Paying' : 'Not Paying' }}
                            </div>
                        </div>
                    </div>
               @endif
            @endforeach
        </div>
    </x-admin.section.card>
    @foreach($quote->repository->getActivityBelowQuantity($paying + $travelling + 1) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'activity',])
    @endforeach
    @foreach($quote->repository->getFlightBelowQuantity($paying + $travelling + 1) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'flight',])
    @endforeach
    @foreach($quote->repository->getTransportBelowQuantity($paying + $travelling + 1) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'transport',])
    @endforeach
    @foreach($quote->repository->getMerchandiseBelowQuantity($paying + $travelling + 1) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'merchandise',])
    @endforeach
    <x-admin.section.card>
        <div class="row">
            @if(flag('quote.convert.reference', false) && $this->orderExists())
                <div class="col-12 fw-bold" style="color: red">
                    An order with the same reference already exists, so the reference will not be maintained
                </div>
            @endif
            <div class="col-10 fw-bold" style="color: red;">
                @if($verifyComponents)
                    Some components may have incorrect quantity values. You should double-check before continuing.
                @endif
            </div>
            <div class="col-2">
                <button class="btn btn-{{$verifyComponents?'danger':'success'}}" wire:click="convert">
                    Convert to Order
                </button>
            </div>
        </div>
    </x-admin.section.card>
</div>
