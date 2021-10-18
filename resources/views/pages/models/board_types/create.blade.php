@extends('layout.main')

@section('title', 'Create Board Type')

@section('content')
    @include('partials.models.board_types.form', ['action' => route('board-types.store'),])
@endsection
