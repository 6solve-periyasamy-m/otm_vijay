@extends('layout.main')

@section('title', 'Update Board Types')

@section('content')
    @include('partials.models.board_types.form', ['action' => route('board-types.update', ['boardType' => $boardType,]),
      'board_type_name' => $boardType->board_type_name,
    ])
@endsection
