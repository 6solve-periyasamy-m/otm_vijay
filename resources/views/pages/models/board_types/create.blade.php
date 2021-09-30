@extends('layout.main')

@section('title', 'Create Board Types')

@section('content')
  @include('partials.models.board_types.form', ['action' => route('board_types.store'),])
@endsection
