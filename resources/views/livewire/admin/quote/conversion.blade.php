<div>
    <x-admin.section.card>
        <x-slot:title>Travellers</x-slot:title>
        <div class="row">
            @foreach($travellers as $key => $traveller)
                @if ($traveller['id'] === -1)
                    <div class="col-6 row">
                        <x-livewire.input disabled label="Lead Traveller" width="10" value="{{$traveller['name']}}" />
                        <div class="col-2 my-auto">
                            <div class="btn btn-{{ $traveller['paying'] ? 'success' : 'danger' }}">
                                {{ $traveller['paying'] ? 'Paying' : 'Not Paying' }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-6 row">
                        <x-livewire.input label="Traveller {{ $key }}" wire:model="travellers.{{$key}}.name" width="10" />
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
    @foreach($quote->repository->getActivityBelowQuantity($paying + $travelling) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'activity',])
    @endforeach
    @foreach($quote->repository->getFlightBelowQuantity($paying + $travelling) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'flight',])
    @endforeach
    @foreach($quote->repository->getTransportBelowQuantity($paying + $travelling) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'transport',])
    @endforeach
    @foreach($quote->repository->getMerchandiseBelowQuantity($paying + $travelling) as $quoteComponent)
        @include('partials.admin.quote.conversion.component', ['type' => 'merchandise',])
    @endforeach
</div>