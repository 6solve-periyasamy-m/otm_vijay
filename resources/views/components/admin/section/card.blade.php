<div class="card">
    <div class="card-body">
        @if(isset($header))
            <div class="card-title">
                <h4 class="fw-bold">{{ $header }}</h4>
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
