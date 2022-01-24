<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    <a class="d-inline-flex btn btn-block btn-{{ $color ?? 'primary' }}" href="{{ $route }}">{{ $name }}</a>
</div>
