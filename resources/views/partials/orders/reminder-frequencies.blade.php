<div class="card">
    <div class="card-body row">
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders', ['max' => 14, 'min' => 7]) }}">14 Days</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders', ['max' => 7, 'min' => 0]) }}">7 Days</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders', ['max' => 0, 'min' => -1]) }}">Today</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders', ['max' => -1, 'min' => -7]) }}">1 Day Overdue</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders', ['max' => -7, 'min' => -14]) }}">7 Days Overdue</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders', ['max' => -14, 'min' => -1000]) }}">14 Days Overdue</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body row">
        @include('partials.fields.text', ['name' => 'Maximum Days', 'field' => 'max', 'value' => $max, 'width' => 5])
        @include('partials.fields.text', ['name' => 'Minimum Days', 'field' => 'min', 'value' => $min, 'width' => 5])
        <div class="form-group col-12 col-xl-2">
            <label></label>
            <a class="form-control mx-auto btn btn-block btn-primary" onclick="event.preventDefault();showNewRange()">Show Range</a>
        </div>
    </div>
</div>
@push('header-stack')
    <script type="text/javascript">
        function showNewRange() {
            let max = $('#max-input').val();
            let min = $('#min-input').val();
            window.location = '{{ route('orders.reminders') }}/' + max + '/' + min;
        }
    </script>
@endpush
