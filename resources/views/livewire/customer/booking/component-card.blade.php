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
        <div class="component-text row mx-2">
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
        <div class="row">
            <div class="col-xl-8 col-lg-8 my-auto">
                <select class="booking-upgrade">
                    <option>Signature Lux Hotel by ONOMO Foreshore, Double Room (Occupancy 2) (Self-Catered)</option>
                    <option>Signature Lux Hotel by ONOMO Foreshore, Double Room (Occupancy 2) (Self-Catered)</option>
                    <option>Signature Lux Hotel by ONOMO Foreshore, Double Room (Occupancy 2) (Self-Catered)</option>
                    <option>Signature Lux Hotel by ONOMO Foreshore, Double Room (Occupancy 2) (Self-Catered)</option>
                </select>
            </div>
            <div class="col-6 col-lg-2 col-xl-2 row border-right mx-auto">
                <div class="col-12 border-bottom text-center buy-header">
                    Buy for
                </div>
                <div class="col-6 border-right">
                    <button class="btn btn-success text-dark buy-button">
                        One
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-success text-dark buy-button">
                        All
                    </button>
                </div>
            </div>
            <div class="col-6 col-lg-2 col-xl-2 row mx-auto">
                <div class="col-12 border-bottom text-center buy-header">
                    Remove for
                </div>
                <div class="col-6 border-right">
                    <button class="btn btn-warning text-dark buy-button">
                        One
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-warning text-dark buy-button">
                        All
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
