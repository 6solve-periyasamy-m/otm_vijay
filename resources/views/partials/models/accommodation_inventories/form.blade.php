@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let roomTypeSelect = $('#room_type_id-input');
            roomTypeSelect.select2({
                ajax: {
                    url: '{{ route('api.room-types.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; }
                }
            });
            $.ajax({ url: '{{ route('api.room-types.selected', ['id' => $room_type_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    roomTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    roomTypeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let boardTypeSelect = $('#board_type_id-input');
            boardTypeSelect.select2({
                ajax: {
                    url: '{{ route('api.board-types.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; }
                }
            });
            $.ajax({ url: '{{ route('api.board-types.selected', ['id' => $room_type_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    boardTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    boardTypeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
        });
    </script>
@endsection
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">                
                <div class="form-group col-12">
                    <label for="room_type_id-input">Room Type</label>
                    <div class='d-flex'>
                        <select name="room_type_id" class="form-control" id="room_type_id-input"> </select>
                        <a href="{{ route('room-types.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="board_type_id-input">Board Type</label>
                    <div class='d-flex'>
                        <select name="board_type_id" class="form-control" id="board_type_id-input"></select>
                        <a href="{{ route('board-types.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-xl-6">
                    <label for="check_in-input">Check In Date Time</label>
                    <input type="datetime-local" name="check_in" value="{{ isset($check_in) ? $check_in->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="check_in-input">

                    <p></p>
                    <input type="checkbox" name="check_in_time_confirmed" class="form-check-input" @if(isset($check_in_time_confirmed) && $check_in_time_confirmed == 1) checked @endif
                        id="check_in_time_confirmed-input">
                    <label for="check_in_time_confirmed-input" class="form-check-label">Check In Time Confirmed</label>
                </div>                                                
                <div class="form-group col-xl-6">
                    <label for="check_out-input">Check Out</label>
                    <input type="datetime-local" name="check_out" value="{{ isset($check_out) ? $check_out->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="check_out-input">
                    <p></p>
                    <input type="checkbox" name="check_out_time_confirmed" class="form-check-input" @if(isset($check_out_time_confirmed) && $check_out_time_confirmed == 1) checked @endif
                        id="check_out_time_confirmed-input">
                    <label for="check_out_time_confirmed-input" class="form-check-label">Check Out Time Confirmed</label>
                </div>                
                <div class="form-group col-12">
                    <input type="checkbox" name="fit_selectable" class="form-check-input" id="fit_selectable-input" @if(isset($fit_selectable) && $fit_selectable == 1) checked @endif >
                    <label for="fit_selectable-input" class="form-check-label">Fit Selectable</label>
                </div>
                <div class="form-group col-12">
                    <label for="stock-input">Stock</label>
                    <input name="stock" value="{{ $stock ?? "" }}" class="form-control" id="stock-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="purchase_price-input">Purchase Price</label>
                    <input name="purchase_price" value="{{ $purchase_price ?? "" }}" class="form-control" id="purchase_price-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="sales_price-input">Sales Price</label>
                    <input name="sales_price" value="{{ $sales_price ?? "" }}" class="form-control" id="sales_price-input">
                </div>                
                <div class="form-group">
                    <label for="currency-input">Currency</label>
                    <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
                </div>                
                <div class="form-group">
                    <label for="notes-input">Notes</label>
                    <textarea class="form-control" id="notes-input" name="notes" rows="2">{{ $notes ?? "" }}</textarea>                    
                </div>                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
