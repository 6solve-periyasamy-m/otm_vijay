@push('footer-ready')
    @include('partials.fields.selector.script', ['field' => $field, 'id' => $value ?? 0, 'additionalParams' => $additionalParams ?? "",])
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($name)<label for="{{ $field }}-input" class="{{ $labelClasses ?? '' }} @error($field) text-danger @enderror">{{ $name }}</label>@endisset
    <div class="d-flex">
        <select class="form-control {{ $field }}-input" id="{{ $field }}-input" name="{{ $field }}" onchange="{{$onchange ?? ''}}"></select>
        <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success d-inline ms-1" onclick="{{$onclick ?? ''}}">+</a>
    </div>
</div>
 
<script>
    $(document).ready(function(){
        $('select').on('select2:open', function() {
            $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Type here to search...');
            $('.select2-search--dropdown .select2-search__field').css('border', '2px solid #242222');
        });
    })
</script>