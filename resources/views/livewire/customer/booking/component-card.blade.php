<div class="card mx-1 @if(!$this->component->owned) unowned @endif">
    <div class="card-body">
        <div class="component-header row">
            <div class="col-9 my-auto d-flex">
                <div class="px-2">
                    {{ Icon::accommodation() }}
                </div>
                {!! $this->component->name !!}
            </div>
            <div class="col-2 component-dates fw-bold my-auto">
                {{ $this->component->tour_component_type !== 'Included' ? f_currency($this->component->component->getSalesPrice()) : 'Included' }}
            </div>
        </div>
        <div class="component-text">
            <div class="mx-2 row">
                @if($this->component->image !== null)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6 border-right">
                        <img src="{{ asset($this->component->image) }}" class="booking-image"/>
                    </div>
                    <div class="col-xl-10 col-lg-9 col-md-8 col-6">
                        @include('partials.customer.booking.component.attributes', ['attributes' => $this->component->attributes])
                        {{ $this->component->description }}
                    </div>
                @else
                    @include('partials.customer.booking.component.attributes', ['attributes' => $this->component->attributes])
                    {{ $this->component->description }}
                @endif
            </div>
        </div>
        @if(!$this->component->owned || ($this->component->owned && $this->component->tour_component_type === 'Add-on') || sizeof($this->component->getAvailableUpgrades()) > 1)
        <div class="component-upgrades row">
            <div class="col-xl-10 col-lg-10 col-6 my-auto">
                @if(sizeof($this->component->getAvailableUpgrades()) > 1)
                    <select class="booking-upgrade" wire:model="upgrade">
                        <option selected>Please Select an Upgrade</option>
                        @foreach($this->component->getAvailableUpgrades() as $upgrade)
                            @php
                                $owned = $this->component->equals($upgrade);
                            @endphp
                            <option value="{{ json_encode($upgrade->toLivewire()) }}" @if($owned) disabled @endif>{!! $upgrade->upgrade_name !!} @if($owned)(Current)@endif</option>
                        @endforeach
                    </select>
                @elseif($this->component->tour_component_type === 'Add-on')
                    <div class="text-center fw-bold" style="font-size: 1.5rem">Cost per Traveller: {{ f_currency($this->component->component->getSalesPrice()) }}</div>
                @endif
            </div>
            @if($this->component->tour_component_type === 'Add-on' && $this->component->owned)
                @include('partials.customer.booking.component.sell-buttons')
            @else
                @include('partials.customer.booking.component.buy-buttons')
            @endif
        </div>
        @endif
    </div>
    <x-wire-loader longest/>
</div>
