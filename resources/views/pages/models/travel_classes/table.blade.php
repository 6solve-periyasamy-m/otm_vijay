@extends('layout.main')

@section('title', 'Update Travel Classes')

@section('content')
<a class="btn btn-primary" href="{{ route('travel_classes.create') }}">Create New</a><table id="travelClass" style="width: 100%;" class="table table-striped">
  <thead class="thead-dark">
  <tr>
    <th scope="col">Title</th>
    <th scope="col">Actions</th>
  </tr>
  </thead>
  @foreach($travelClasses as $travelClass)
    @include('partials.models.travel_classes.row', [
      'travelClass' => $travelClass,
      'title' => $travelClass->title,
    ])
  @endforeach
</table>
@endsection
