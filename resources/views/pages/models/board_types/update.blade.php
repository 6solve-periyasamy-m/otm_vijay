@extends('layout.main')

@section('title', 'Update Board Type')

@section('content')
    @include('partials.models.board_types.form', ['action' => route('board-types.update', ['boardType' => $boardType,]),
      'name' => $boardType->name,
    ])
@endsection
