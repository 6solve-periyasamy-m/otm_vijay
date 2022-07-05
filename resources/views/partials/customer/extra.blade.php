<div class="extra-box">
    <div class="extra-header row">
        <div class="col-12 col-xl-10">
            <span class="fw-bold">{{ $data['description'] }}</span>
        </div>
        <div class="col-12 col-xl-1">
            {{ f_currency($data['cost']) }}
        </div>
        <div class="col-12 col-xl-1">
            <input type="checkbox" onchange="checkboxChange(this)" cost="{{ $data['cost'] }}" name="{{ $section }}-{{ $data['id'] }}-toggle" @if($data['owned']) checked @endif @if(!$data['change']) disabled @endif>
        </div>
    </div>
    @if(array_key_exists('upgrades', $data) && sizeof($data['upgrades']) > 0)
    <div class="header-upgrades">
        <div class="row">
            <div class="col-12 col-xl-10">
                Basic Package
            </div>
            <div class="col-12 col-xl-1">
                {{ f_currency($data['cost'])  }}
            </div>
            <div class="col-12 col-xl-1">
                <input type="radio" onchange="radioChange(this)" cost="{{ $data['cost'] }}" name="{{ $section }}-{{ $data['id'] }}" value="0" @if(!$data['upgraded']) checked previous @endif>
            </div>
        </div>
        @foreach($data['upgrades'] as $upgrade)
            <div class="row">
                <div class="col-12 col-xl-10">
                    <span class="fw-bold">-></span> {{ $upgrade['description'] }}
                </div>
                <div class="col-12 col-xl-1">
                    {{ f_currency($upgrade['cost']) }}
                </div>
                <div class="col-12 col-xl-1">
                    <input type="radio" onchange="radioChange(this)" cost="{{ $upgrade['cost'] }}" name="{{ $section }}-{{ $data['id'] }}" value="{{ $upgrade['id'] }}" @if($upgrade['owned']) checked previous @endif>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
