@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let eventSelect = $('#event_id-input');
            eventSelect.select2({
                ajax: {
                    url: '{{ route('api.events.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.events.selected', ['id' => $event_id ?? 0, ]) }}', })
                .then(function (data) {
                    eventSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    eventSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
        });
    </script>
@endsection
<div class='card'>
    <div class='card-body'>
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="event_id-input">Event</label><br />
                    <div class="d-flex">
                        <select name="event_id" class="form-control" id="event_id-input"></select>
                        <a href="{{ route('events.create') }}" target="_blank" class="btn btn-success d-inline mt-0 ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="title-input">Title</label>
                    <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
                </div>                
                <div class="form-group col-12">
                    <label for="description-input">Description</label>
                    <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="date_from-input">Date From</label>
                    <input type="date" name="date_from" value="{{ $date_from ?? "" }}" class="form-control" id="date_from-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="date_to-input">Date To</label>
                    <input type="date" name="date_to" value="{{ $date_to ?? "" }}" class="form-control" id="date_to-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="base_price_per_person-input">Base Price Per Person</label>
                    <input name="base_price_per_person" value="{{ $base_price_per_person ?? "" }}" class="form-control"
                        id="base_price_per_person-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="margin-input">Margin</label>
                    <input name="margin" value="{{ $margin ?? "" }}" class="form-control" id="margin-input" type="number">
                </div>                
                <div class="form-group col-12">
                    <label for="single_occupancy_surcharge-input">Single Occupancy Surcharge</label>
                    <input name="single_occupancy_surcharge" value="{{ $single_occupancy_surcharge ?? "" }}" class="form-control"
                        id="single_occupancy_surcharge-input">
                </div>                
                <div class="form-group col-12">
                    <input type="checkbox" name="stock_control_active" class="form-check-input" @if(isset($stock_control_active) && $stock_control_active == 1) checked @endif
                    id="stock_control_active-input">
                    <label for="stock_control_active-input" class="form-check-label">Stock Control Active</label>
                </div>                
                <div class="form-group col-12">
                    <label for="stock-input">Stock</label>
                    <input name="stock" value="{{ $stock ?? "" }}" class="form-control" id="stock-input">
                </div>                
                <div class="form-group col-12">
                    <label for="booking_form_url-input">Booking Form Url</label>
                    <input name="booking_form_url" value="{{ $booking_form_url ?? "" }}" class="form-control"
                        id="booking_form_url-input">
                </div>                
                <div class="form-group col-12">
                    <label for="tour_colour_id-input">Tour Colour Id</label>
                    <input name="tour_colour_id" value="{{ $tour_colour_id ?? "" }}" class="form-control" id="tour_colour_id-input">
                </div>                
                <div class="form-group col-12">
                    <input type="checkbox" name="is_active" class="form-check-input" @if(isset($is_active) && $is_active == 1) checked @endif
                    id="is_active-input">
                    <label for="is_active-input">Is Active</label>
                </div>                
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <textarea rows="2" class='form-control' id="notes-input" name="notes">{{ $notes ?? "" }}</textarea>                    
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
