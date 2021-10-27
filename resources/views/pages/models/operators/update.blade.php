@extends('layout.master')

@section('title', 'Update Operator')

@section('content')
    @include('partials.models.operators.form', ['action' => route('operators.update', ['operator' => $operator,]),
      'name' => $operator->name,
      'notes' => $operator->notes,
    ])
@endsection
