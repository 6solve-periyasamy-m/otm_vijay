@php /** @var \App\Models\Tour\Tour $tour */ @endphp
<div id="components" role="tabpanel" class="tab-pane fade show active">
    <div id="components-details">
        <table id="components-table" class="datatable table table-striped table-responsive-sm">
            <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Dates</th>
                    <th scope="col">Name</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Ordered</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Sales Price</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tour->repository->getComponents() as $component)
                    <tr>
                        <td>{{ ucwords($component->getComponentType()) }}</td>
                        <td data-sort="{{ $component->getStartTime()->unix() }}">{{ f_datetime($component->getStartTime()) }} to {{ f_datetime($component->getEndTime()) }}</td>
                        <td>{{ $component->getOverview() }}</td>
                        <td>{{ $component->getTourComponentType() }}</td>
                        <td>
                            {{ $component->getUsedStock() }}
                            /{{ $component->getTotalStock() }}<br/>
                            ({{$component->getAvailableStock()}} Available)
                        </td>
                        <td>{{ $component->getOrderedCount() }} Ordered, {{ $component->getBookedCount() }} <abbr title="Bookings created through the form. Will include ones converted to orders">Booked</abbr></td>
                        <td>{{ $component->getInventory()->getPurchasePriceString() }}</td>
                        <td>{{ f_currency($component->getCostToCustomer()) }}</td>
                        <td>{{ $component->getComponentInternalNotes() }}</td>
                        <td class="actions">
                            @if($component->getUpdateLink() !== null)
                                <a href="{{ $component->getUpdateLink() }}"
                                   class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">{{ Icon::edit() }}</span>
                            @endif
                            @if($component->getDeleteLink() !== null && $component->canDelete())
                                <a href="#"
                                   onclick="$('#component-{{$component->getComponentType()}}-{{$component->get()->id}}-delete').submit()"
                                   class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                <form action="{{ $component->getDeleteLink() }}"
                                      method="post"
                                      id="component-{{$component->getComponentType()}}-{{$component->get()->id}}-delete">
                                    @csrf
                                </form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="{{ $component->getDeleteLink() !== null ? 'Has Dependants' : 'Insufficient Permissions' }}">
                                    {{ Icon::delete() }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
