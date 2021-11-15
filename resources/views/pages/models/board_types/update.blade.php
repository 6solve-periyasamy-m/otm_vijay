@extends('layout.form', ['action' => route('board-types.update', ['boardType' => $boardType,]),])

@section('title', 'Update Board Type')

@section('form-body')
    @include('partials.models.board_types.form', [
      'name' => $boardType->name,
    ])
@endsection
