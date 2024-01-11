<div class="card">
    <div class="card-body">
        @if(isset($header) ?? isset($title))
            <div class="card-title">
                @if(isset($title))
                    <h4 class="fw-bold">{{ $title }}</h4>
                @else
                    {{ $header }}
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
