@extends('layout.form', ['action' => route('board-types.store'),])

@section('title', 'Create Board Type')

@section('form-body')
    @include('partials.models.board_types.form')
@endsection
