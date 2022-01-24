<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @include('partials.fields.raw.textarea')
</div>
@push('footer-stack')
    <script type="text/javascript">
        CKEDITOR.replace('{{ $field }}-input');
    </script>
@endpush
