<div class="block width-100 my-auto border-bottom">
    @php $width = sizeof($attributes); @endphp
    @foreach($attributes as $name => $value)
        <div class="width-1-{{$width}} my-1 inline-block text-center py-1 @isset($first) border-left @endisset">
            <span class="block fw-bold fs-14 pb-1">{{ $name }}</span>
            <span class="block fs-14">{{ empty($value) ? "Not Set" : $value }}</span>
        </div>
        @php $first = false; @endphp
    @endforeach
</div>
