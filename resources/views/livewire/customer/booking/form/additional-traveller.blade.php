<div class="card form-horizontal form-material mx-2">
    <div class="card-body row">
        @if(empty($traveller?->id))
            <div class="col-12">
                <span class="fw-bold">Adding a new traveller will reset all rooming assignments back to default</span>
            </div>
        @endif
        <x-customer.input wire:model="traveller.title" width="1" autocomplete="honorific-prefix" required>
            Title
        </x-customer.input>

        <x-customer.input wire:model="traveller.first_name" width="3" autocomplete="given-name" required>
            First Name
        </x-customer.input>

        <x-customer.input wire:model="traveller.middle_names" width="4" autocomplete="additional-name">
            Middle Names
        </x-customer.input>

        <x-customer.input wire:model="traveller.last_name" width="4" autocomplete="family-name" required>
            Last Name
        </x-customer.input>

        <x-customer.input type="date" wire:model="date_of_birth" width="6" autocomplete="bday" required>
            Date of Birth
        </x-customer.input>

        <x-customer.input wire:model="traveller.mobile_number" value="{{ $traveller?->mobile_number ?? '' }}" width="6" autocomplete="tel">
            Primary Phone
        </x-customer.input>
        @if($this->booking->tour?->templates->count() && empty($traveller?->id))
            <div class="form-group col-md-6">
                <label class="col-md-12 mb-0">Ideal Room Type</label>
                <select wire:model="room_type" class="w-100">
                    @foreach($this->getAvailableRooms() as $room)
                        <option value="{{ $room->id }}">{{ $room }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label class="col-md-12 mb-0">Room</label>
                <select wire:model="group" class="w-100">
                    @for($group = 1; $group < 31; $group++)
                        <option value="{{ $group }}">Room {{ $group }}</option>
                    @endfor
                </select>
            </div>
        @endif
        <div class="d-flex" style="flex-direction: row-reverse">
            <button wire:click="save" class="btn btn-success text-white">Save Traveller</button>
        </div>
    </div>
</div>
