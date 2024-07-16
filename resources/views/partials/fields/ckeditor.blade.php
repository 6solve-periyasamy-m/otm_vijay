@php $id = 'a' . str_replace('-', '', \Str::uuid()); @endphp
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @include('partials.fields.raw.textarea')
</div>
@push('footer-stack')
    <script type="text/javascript">
        ClassicEditor
            .create(document.querySelector('#{{ $id }}'), ckConfig)
            .catch(error => console.log(error));
    </script>
@endpush
