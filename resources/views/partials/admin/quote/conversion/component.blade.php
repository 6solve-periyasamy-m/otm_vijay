@php
/**
 * @var \App\Models\Quote\Component\QuoteAccommodation|\App\Models\Quote\Component\QuoteActivity|\App\Models\Quote\Component\QuoteFlight|\App\Models\Quote\Component\QuoteTransport|\App\Models\Quote\Component\QuoteMerchandise $quoteComponent
 * @var array $travellers
 * @var string $type
 */
@endphp
<x-admin.section.card>
    @php $quantity = $this->getQuantity($type, $quoteComponent->id); @endphp
    <x-slot:title>
        {{ $quoteComponent->repository->__toString() }} -
        <span style="{{ $quantity > $quoteComponent->quantity ? 'color: red;' : ''}}">Quantity: {{ $quantity }} of {{ $quoteComponent->quantity }}</span>
    </x-slot:title>
    <div class="row">
        @foreach($travellers as $key => $traveller)
            <div class="col-2">
                @php $has = $this->hasComponent($key, $type, $quoteComponent->id); @endphp
                <button wire:click="toggleComponent({{$key}}, '{{$type}}', {{$quoteComponent->id}})" class="btn btn-outline {{ $has  ? 'btn-outline-success' : 'btn-outline-danger' }}">
                    {{ $has ? Icon::check() : Icon::cross() }} {{ $traveller['name'] ?? "Traveller " . $key }}
                </button>
            </div>
        @endforeach
    </div>
</x-admin.section.card>