@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let quoteSelect = $('#quote_id-input');
            quoteSelect.select2({
                ajax: {
                    url: '{{ route('api.quotes.select') }}',
                    data: function (params) {
                        return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',};
                    }
                }
            });
            $.ajax({url: '{{ route('api.quotes.selected', ['id' => $quote_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }})
                .then(function (data) {
                    quoteSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    quoteSelect.trigger({
                        type: 'select2:select',
                        params: {data: data,}
                    });
                });
            let tourSelect = $('#tour_id-input');
            tourSelect.select2({
                ajax: {
                    url: '{{ route('api.tours.select') }}',
                    data: function (params) {
                        return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',};
                    }
                }
            });
            $.ajax({url: '{{ route('api.tours.selected', ['id' => $tour_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }})
                .then(function (data) {
                    tourSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    tourSelect.trigger({
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
                    <label for="tour_id-input">Tour</label>
                    <div class="d-flex">
                        <select name="tour_id" class="form-control" id="tour_id-input"></select>
                        <a href="{{ route('tours.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label for="quote_id-input">Quote</label>
                    <select style="width: 100%" name="quote_id" class="form-control" id="quote_id-input"></select>
                </div>
                <div class="form-group col-12">
                    <label for="token-input">Token</label>
                    <input name="token" value="{{ $token ?? "" }}" class="form-control" id="token-input">
                </div>
                <div class="form-group col-12">
                    <label for="ordered_on-input">Ordered On</label>
                    <input type="datetime-local" name="ordered_on" value="{{ $ordered_on ?? "" }}" class="form-control" id="ordered_on-input">
                </div>
                <div class="form-group col-12">
                    <label for="internal_notes-input">Internal Notes</label>
                    <input name="internal_notes" value="{{ $internal_notes ?? "" }}" class="form-control" id="internal_notes-input">
                </div>
                <div class="form-group col-12">
                    <label for="external_notes-input">External Notes</label>
                    <input name="external_notes" value="{{ $external_notes ?? "" }}" class="form-control" id="external_notes-input">
                </div>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
