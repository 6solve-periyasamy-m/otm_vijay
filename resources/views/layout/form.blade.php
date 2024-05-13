@extends('layout.master')

@section('content')
    <x-admin.section.card>
        <form id="form-main" action="{{ $action }}" method="post" {!! isset($multipart) && $multipart ? 'enctype="multipart/form-data"' : '' !!} {!! isset($autocomplete) && !$autocomplete ? 'autocomplete="off"' : '' !!}>
            @if(isset($autocomplete) && !$autocomplete)
                <input autocomplete="false" name="__hidden" type="hidden" style="display:none;">
            @endif
            @csrf
            <div class="row">
                @yield('form-body')
            </div>
        </form>
    </x-admin.section.card>
@endsection
