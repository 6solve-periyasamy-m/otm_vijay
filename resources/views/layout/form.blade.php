@extends('layout.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="form-main" action="{{ $action }}" method="post" {!! isset($multipart) && $multipart ? 'enctype="multipart/form-data"' : '' !!}>
                @csrf
                <div class="row">
                    @yield('form-body')
                </div>
            </form>
        </div>
    </div>
@endsection
