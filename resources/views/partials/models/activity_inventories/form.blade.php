@section('header-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let ticketType = $('#ticket_type_id-input');
            ticketType.select2({
                ajax: {
                    url: '{{ route('api.ticket-types.select') }}',
                    data: function (params) {
                        return {filter: params.term,};
                    }
                }
            });
            $.ajax({url: '{{ route('api.ticket-types.selected', ['id' => $ticket_type_id ?? 0, ]) }}',})
                .then(function (data) {
                    ticketType.append(new Option(data.text, data.id, true, true)).trigger('change');

                    ticketType.trigger({
                        type: 'select2:select',
                        params: {data: data,}
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
                    <label for="ticket_type_id-input">Ticket Type</label>
                    <div class="d-flex">
                        <select name="ticket_type_id" class="form-control" id="ticket_type_id-input"></select>
                        <a href="{{ route('ticket-types.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-xl-6">
                    <label for="starts_at-input">Activity Start Date Time</label>
                    <input type="datetime-local" name="starts_at" value="{{ isset($starts_at) ? $starts_at->format('Y-m-d\TH:i') : "" }}" class="form-control" onchange="changeDate(indate, outdate)"
                        id="starts_at-input">
                </div>                
                <div class="form-group col-xl-6">
                    <label for="ends_at-input">Activity End Date Time</label>
                    <input type="datetime-local" name="ends_at" value="{{ isset($ends_at) ? $ends_at->format('Y-m-d\TH:i') : "" }}" onchange="removeAutoset(indate, outdate);"
                        class="form-control autoset" id="ends_at-input">
                </div>                
                <div class="form-group col-12">
                    <input type="checkbox" name="fit_selectable" class="form-check-input" id="fit_selectable-input" @if(isset($fit_selectable) && $fit_selectable == 1) checked @endif>
                    <label for="fit_selectable-input">Fit Selectable</label>
                </div>                
                <div class="form-group col-12">
                    <label for="stock-input">Stock</label>
                    <input name="stock" value="{{ $stock ?? "" }}" class="form-control" id="stock-input">
                </div>                
                <div class="form-group col-12">
                    <label for="purchase_price-input">Purchase Price</label>
                    <input name="purchase_price" value="{{ $purchase_price ?? "" }}" class="form-control" id="purchase_price-input">
                </div>                
                <div class="form-group col-12">
                    <label for="sales_price-input">Sales Price</label>
                    <input name="sales_price" value="{{ $sales_price ?? "" }}" class="form-control" id="sales_price-input">
                </div>                
                <div class="form-group col-12">
                    <label for="currency-input">Currency</label>
                    <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
                </div>                
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <textarea name="notes" class="form-control" id="notes-input" rows="2">{{ $notes ?? ""}} </textarea>                    
                </div>                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@section('footer-script')
    <script type="text/javascript">
        let indate = $('#starts_at-input');
        let outdate = $('#ends_at-input');
        function changeDate(invar, outvar) {
            if (outvar.hasClass('autoset')) {
                outvar.val(invar.val());
            }
        }
        function removeAutoset(invar, outvar) {
            if (outvar.hasClass('autoset') && outvar.val() !== invar.val()) {
                outvar.removeClass('autoset')
            }
        }
    </script>
@endsection
