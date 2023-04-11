<div class="d-flex flex-between border-bottom mb-1">
    @foreach($attributes as $name => $value)
        <div class="w-100 text-center @isset($first) border-left @endisset">
            <span class="d-block fw-bold fs-14">{{ $name }}</span>
            <span class="d-block fs-14">{{ $value }}</span>
        </div>
        @php $first = false; @endphp
    @endforeach
</div>
