@php /** @var \App\Models\Merchandise\Merchandise $merchandise */ @endphp
<div class="card">
    <div class="card-body">
        <table class="datatable table table-striped" id="inventory">
            <thead>
            <tr>
                <th>Icon</th>
                <th>Variant</th>
                <th>Size</th>
                <th>FIT Selectable</th>
                <th>Purchase Price</th>
                <th>Sales Price</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($merchandise->inventory as $inventory)
                <tr>
                    <td><img src="{{ $inventory->asset }}" class="image tiny"/></td>
                    <td>{{ $inventory->variant->name }}</td>
                    <td>{{ $inventory->size?->name ?? 'No Size' }}</td>
                    <td>{{ f_bool($inventory->fit_selectable) }}</td>
                    <td>{{ f_currency($inventory->purchase_price) }}</td>
                    <td>{{ f_currency($inventory->sales_price) }}</td>
                    <td>{{ $inventory->internal_notes }}</td>
                    <td>
                        <a href="{{route('merchandise.inventory.duplicate', ['merchandise' => $merchandise, 'inventory' => $inventory, 'view' => 'detailed',])}}"
                           class="btn btn-sm btn-outline-info mb-1">
                            {{ Icon::copy() }}
                        </a>
                        <a href="{{route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory, 'view' => 'detailed',])}}"
                           class="btn btn-sm btn-outline-success mb-1">
                            {{ Icon::edit() }}
                        </a>
                        <a href="javascript:$('#inventory-{{ $inventory->id }}-delete').submit();"
                           class="btn btn-sm btn-outline-danger mb-1">
                            {{ Icon::delete() }}
                        </a>
                        <form id="inventory-{{ $inventory->id }}-delete"
                              action="{{ route('merchandise.inventory.delete', ['merchandise' => $merchandise, 'inventory' => $inventory, 'view' => 'detailed',]) }}"
                              method="POST"
                              style="display: none;">{{ csrf_field() }}</form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

