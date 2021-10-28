@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let quoteSelect = $('#quote_id-input');
            quoteSelect.select2({
                ajax: {
                    url: '{{ route('api.quotes.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            let tourSelect = $('#tour_id-input');
            tourSelect.select2({
                ajax: {
                    url: '{{ route('api.tours.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            let leadBookerSelect = $('#customer_id-input');
            leadBookerSelect.select2({
                ajax: {
                    url: '{{ route('api.customers.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="tour_id-input">Tour</label>
        <select style="width: 100%"  name="tour_id" class="form-control" id="tour_id-input"></select>
        <a href="{{ route('tours.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div class="form-group">
        <label for="quote_id-input">Quote</label>
        <select style="width: 100%" name="quote_id" class="form-control" id="quote_id-input"></select>
    </div>
    <p></p>
    <div class="form-group">
        <label for="customer_id-input">Lead Booker</label>
        <select style="width: 100%" name="customer_id" class="form-control" id="customer_id-input"></select>
        <a href="{{ route('customers.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div class="form-group">
        <label for="token-input">Token</label>
        <input name="token" value="{{ $token ?? "" }}" class="form-control" id="token-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="booking_reference-input">Booking Reference</label>
        <input name="booking_reference" value="{{ $booking_reference ?? "" }}" class="form-control"
               id="booking_reference-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="ordered_on-input">Ordered On</label>
        <input type="datetime-local" name="ordered_on" value="{{ $ordered_on ?? "" }}" class="form-control" id="ordered_on-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="internal_notes-input">Internal Notes</label>
        <input name="internal_notes" value="{{ $internal_notes ?? "" }}" class="form-control" id="internal_notes-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="external_notes-input">External Notes</label>
        <input name="external_notes" value="{{ $external_notes ?? "" }}" class="form-control" id="external_notes-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
