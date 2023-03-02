@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="form-main" action="{{ $action }}" method="post" {!! isset($multipart) && $multipart ? 'enctype="multipart/form-data"' : '' !!} {!! isset($autocomplete) && !$autocomplete ? 'autocomplete="off"' : '' !!}>
                @if(isset($autocomplete) && !$autocomplete)
                    <input autocomplete="false" name="__hidden" type="hidden" style="display:none;">
                @endif
                @csrf
                <div class="row">
                    @yield('form-body')
                </div>
            </form>
        </div>
    </div>
@endsection
