<div class="form-group col-12 row {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($value)
    <div class="col-4 my-auto">
        <img src="{{ asset($value) }}" class="image small"/>
    </div>
    @endisset
    <div class="col-8">
        @include('partials.fields.raw.file')
    </div>
</div>
