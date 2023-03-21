<span class="d-inline">
    <span class="fw-bold">{{ $name }}</span> should lock
    <div class="d-inline-block col-1">
        @include('partials.fields.raw.text', ['name' => null, 'field' => "{$field}_lock", 'value' => setting("{$field}.lock", 0),])
    </div>
    days before the tour starts and unlock again
    <div class="d-inline-block col-1">
        @include('partials.fields.raw.text', ['name' => null, 'field' => "{$field}_unlock", 'value' => setting("{$field}.unlock", 0),])
    </div>
    days after the tour as ended.
</span>
