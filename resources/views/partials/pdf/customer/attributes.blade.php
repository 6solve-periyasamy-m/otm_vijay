<div class="block width-100 my-auto">
    @php $width = sizeof($attributes); @endphp
    @foreach($attributes as $name => $value)
        <div class="width-1-{{$width}} text-center @isset($first) border-left @endisset">
            <span class="block fw-bold fs-14">{{ $name }}</span>
            <span class="block fs-14">{{ $value }}</span>
        </div>
        @php $first = false; @endphp
    @endforeach
</div>
