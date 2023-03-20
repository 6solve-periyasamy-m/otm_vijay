<span class="d-inline">
    <span class="fw-bold">{{ $name }}</span> should lock
    <div class="d-inline-block col-1">
        @include('partials.admin.system.settings.lock-dropdown', ['name' => null, 'field' => "{$field}_lock", 'value' => setting("{$field}.lock", 30), 'classes' => 'd-inline'])
    </div>
    before the tour starts and unlock again
    <div class="d-inline-block col-1">
        @include('partials.admin.system.settings.lock-dropdown', ['name' => null, 'field' => "{$field}_unlock", 'value' => setting("{$field}.unlock", 0), 'lock' => false])
    </div>
    after the tour as ended.
</span>
