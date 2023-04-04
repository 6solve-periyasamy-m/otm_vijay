<div class="card">
    <div class="card-body">
        <div class="component-header row">
            <div class="col-8">
                {{ $this->component->name }}
            </div>

            <div class="col-4 component-dates">
                {{ f_datetime($this->component->start) }} to {{ f_datetime($this->component->end) }}
            </div>
        </div>
        <div class="component-text row">
            <div class="mx-2">
                @if($this->component->image !== null)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6 border-right">
                        <img src="{{ asset($this->component->image) }}" class="booking-image"/>
                    </div>
                    <div class="col-xl-10 col-lg-9 col-md-8 col-6">
                        {{ $this->component->description }}
                    </div>
                @else
                    {{ $this->component->description }}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8 col-lg-8 my-auto">
                @if(sizeof($this->component->getAvailableUpgrades()) > 1)
                    <select class="booking-upgrade" wire:model="upgrade">
                        <option selected>Please Select an Upgrade</option>
                        @foreach($this->component->getAvailableUpgrades() as $upgrade)
                            @php
                                $owned = $this->component->equals($upgrade);
                            @endphp
                            <option value="{{ json_encode($upgrade->toLivewire()) }}" @if($owned) disabled @endif>{{ $upgrade->name }} @if($owned)(Current)@endif</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-6 col-lg-2 col-xl-2 row border-right mx-auto">
                <div class="col-12 border-bottom text-center buy-header">
                    Buy for
                </div>
                <div class="col-6 border-right">
                    <button class="btn btn-success text-dark buy-button" wire:click="buyOne">
                        One
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-success text-dark buy-button" wire:click="buyAll">
                        All
                    </button>
                </div>
            </div>
            <div class="col-6 col-lg-2 col-xl-2 row mx-auto">
                <div class="col-12 border-bottom text-center buy-header">
                    Remove for
                </div>
                <div class="col-6 border-right">
                    <button class="btn btn-warning text-dark buy-button" wire:click="sellOne">
                        One
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-warning text-dark buy-button" wire:click="sellAll">
                        All
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
