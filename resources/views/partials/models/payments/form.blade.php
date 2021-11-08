@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let methodSelect = $('#payment_method_id-input');
            methodSelect.select2({
                ajax: {
                    url: '{{ route('api.payment-method.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({ url: '{{ route('api.payment-method.selected', ['id' => $payment_method_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    methodSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    methodSelect.trigger({
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
                    <label for="payment_method_id-input">Payment Method</label>
                    <select style="width: 100%" name="payment_method_id" class="form-select"
                            id="payment_method_id-input"></select>
                </div>                
                <div class="form-group col-12">
                    <label for="amount-input">Amount</label>
                    <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
                </div>
                <div class="form-group col-12">
                    <label for="paid_on-input">Paid On</label>
                    <input type="datetime-local" name="paid_on" value="{{ $paid_on ?? "" }}" class="form-control" id="paid_on-input">
                </div>
                <div class="form-group col-12">
                    <label for="payment_type-input">Payment Type</label>
                    <select name="payment_type" class="form-select" id="payment_type-input">
                        <option value="Deposit" @if(isset($payment_type) && $payment_type == "Deposit") selected @endif>Deposit</option>
                        <option value="Installment" @if(isset($payment_type) && $payment_type == "Installment") selected @endif>Installment</option>
                        <option value="Refund" @if(isset($payment_type) && $payment_type == "Refund") selected @endif>Refund</option>
                    </select>
                </div>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
