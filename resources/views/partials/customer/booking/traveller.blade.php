@php /** @var \App\Models\Booking\BookingTraveller $traveller */ @endphp
<div class="card customer-section" customer="{{ $traveller?->id ?? 0 }}">
    <div class="card-body row">
        <input type="hidden" name="additional[{{ $number }}][id]" value="{{ $traveller?->id ?? 0 }}">
        <x-customer.input name="additional[{{ $number }}][title]" value="{{ $traveller?->title ?? '' }}" width="1" autocomplete="honorific-prefix" required>
            Title
        </x-customer.input>

        <x-customer.input name="additional[{{ $number }}][first_name]" value="{{ $traveller?->first_name ?? '' }}" width="3" autocomplete="given-name" required>
            First Name
        </x-customer.input>

        <x-customer.input name="additional[{{ $number }}][middle_names]" value="{{ $traveller?->middle_names ?? '' }}" width="4" autocomplete="additional-name">
            Middle Names
        </x-customer.input>

        <x-customer.input name="additional[{{ $number }}][last_name]" value="{{ $traveller?->last_name ?? '' }}" width="4" autocomplete="family-name" required>
            Last Name
        </x-customer.input>

        <x-customer.input type="date" name="additional[{{ $number }}][date_of_birth]" value="{{ $traveller?->date_of_birth?->format('Y-m-d') ?? '' }}" width="3" autocomplete="bday" required>
            Date of Birth
        </x-customer.input>

        <x-customer.input name="additional[{{ $number }}][email_address]" value="{{ $traveller?->email_address }}" width="6" autocomplete="email">
            Email Address
        </x-customer.input>

        <x-customer.input name="additional[{{ $number }}][mobile_number]" value="{{ $traveller?->mobile_number ?? '' }}" width="3" autocomplete="tel">
            Mobile Number
        </x-customer.input>

        <div class="form-group col-md-6">
            <label class="col-md-12 mb-0">Ideal Room Type</label>
            <select name="additional[{{ $number }}][room_type_id]" class="w-100">
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @if(isset($traveller?->room_type) && $room->id == $traveller?->room_type->id) selected @endif>
                        {{ $room }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label class="col-md-12 mb-0">Room Sharing Group</label>
            <select name="additional[{{ $number }}][group_id]" class="w-100">
                @for($group = 1; $group < 31; $group++)
                    <option value="{{ $group }}">Room {{ $group }}</option>
                @endfor
            </select>
        </div>

        <a class="btn btn-danger float-end text-white" href="" onclick="event.preventDefault();removeCustomer(this)">
            <i class="icon-trash"></i>
            Remove Customer
        </a>
    </div>
</div>
