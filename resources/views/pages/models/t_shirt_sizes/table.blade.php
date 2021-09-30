@extends('layout.main')

@section('title', 'Update T Shirt Sizes')

@section('content')
<a class="btn btn-primary" href="{{ route('t_shirt_sizes.create') }}">Create New</a><table id="tShirtSize" style="width: 100%;" class="table table-striped">
  <thead class="thead-dark">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Actions</th>
  </tr>
  </thead>
  @foreach($tShirtSizes as $tShirtSize)
    @include('partials.models.t_shirt_sizes.row', [
      'tShirtSize' => $tShirtSize,
      'name' => $tShirtSize->name,
    ])
  @endforeach
</table>
@endsection
