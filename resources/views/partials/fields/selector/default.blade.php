@push('footer-ready')
    @include('partials.fields.selector.script', ['field' => $field, 'id' => $value ?? 0, 'additionalParams' => $additionalParams ?? "",])
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? '' }}">
    <label for="{{ $field }}-input">{{ $name }}</label>
    <select style="width: 100%" name="{{ $field }}" class="form-control {{ $field }}-input" id="{{ $field }}-input"></select>
</div>

<script>
    $(document).ready(function(){
        $('select').on('select2:open', function() {
            $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Type here to search...');
            $('.select2-search--dropdown .select2-search__field').css('border', '2px solid #242222');
        });
    })
</script>


