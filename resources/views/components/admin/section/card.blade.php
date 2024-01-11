<x-admin.section.card>
    @if(isset($header))
        <div class="card-title">
            <h4 class="fw-bold">{{ $header }}</h4>
        </div>
    @endif
    {{ $slot }}
</x-admin.section.card>
