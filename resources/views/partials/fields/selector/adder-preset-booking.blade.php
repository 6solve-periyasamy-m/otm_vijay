@push('header-ready')
    let {{ $field }}Select = $('.{{ $field }}-input');
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($name)<label for="{{ $field }}-input">{{ $name }}</label>@endisset
    <div class="d-flex">
        <select class="form-control {{ $field }}-input" id="{{ $field }}-input" name="{{ $field }}">
            @foreach($options as $key => $value)
                <option value="{{ $key }}" @if($selected == $key) selected @endif @if($value['disabled']) disabled @endif>{{ $value['name'] }}</option>
            @endforeach
        </select>
        <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success d-inline ms-1" onclick="{{$onclick ?? ''}}">+</a>
    </div>
</div>
