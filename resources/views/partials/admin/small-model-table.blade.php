@php
    /** @var string $repository */
@endphp

<div class="card">
    <div class="card-body">
        <div class="card-title d-flex justify-content-between">
            <h4 class="fw-bold">{{ $repository::getName() }}</h4>
            @if($repository::getCreateUrl() !== null)
                <a class="btn btn-primary" href="{{ $repository::getCreateUrl() }}">
                    <i class="icon-plus"></i>
                    <span>Create New</span>
                </a>
            @endif
        </div>
        <table style="width: 100%;" class="table table-striped datatable">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Details</th>
                <th scope="col">Related</th>
                <th scope="col">{{ __('custom.table.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($repository::getAll() as $model)
                <tr>
                    <td>{{ $model->repository->__toString() }}</td>
                    <td>{{ $model->repository->getRelatedCount() }}</td>
                    <td class="actions">
                        @if($model->repository->getEditUrl() !== null)
                            <a href="{{$model->repository->getEditUrl()}}" class="btn btn-sm btn-outline-success mb-1">
                                <i class="icon-note"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                        @endif
                        @if($model->repository->getDeleteUrl() !== null)
                            <a href="javascript:$('#{{$repository::getSafeName()}}-{{ $model->id }}-delete').submit();" class="btn btn-sm btn-outline-danger mb-1">
                                <i class="icon-trash"></i>
                            </a>
                            <form id="{{$repository::getSafeName()}}-{{ $model->id }}-delete" action="{{$model->repository->getDeleteUrl()}}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-trash"></i>
                                </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
