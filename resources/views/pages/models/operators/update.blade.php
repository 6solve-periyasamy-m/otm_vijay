@extends('layout.form', ['action' => route('operators.update', ['operator' => $operator,]),])

@section('title', 'Update Operator')

@section('form-body')
    @include('partials.models.operators.form', [
      'name' => $operator->name,
      'notes' => $operator->notes,
    ])
@endsection
