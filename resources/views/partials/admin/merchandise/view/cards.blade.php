@php /** @var \App\Models\Merchandise\Merchandise $merchandise */ @endphp
<div class="inventory-container">
    @foreach($merchandise->repository->getInventory() as $inventory)
        <div class="card inventory">
            <div class="card-body d-flex">
                <div class="item">
                    <img src="{{ $inventory->asset }}" class="image medium">
                </div>
                <div class="item vertical-divider"></div>
                <div class="item">
                    <div>
                        <h5 class="fw-bold">{{ $inventory->variant->name }} @isset($inventory->size) @endif</h5>
                    </div>
                    <hr class="splitter">
                    <select onchange="update(this)">
                        <option selected value="{{ $inventory->id }}"
                                purchase="{{ f_currency($inventory->purchase_price) }}"
                                sales="{{ f_currency($inventory->sales_price) }}"
                                stock="{{ $inventory->stock }}"
                                available="{{ $inventory->available_stock }}"
                                used="{{ $inventory->used_stock }}"
                                edit="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}"
                                delete="{{ route('merchandise.inventory.delete', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}"
                        >
                            {{ $inventory->size?->name ?? 'No Size' }} ({{ f_currency($inventory->sales_price) }})
                        </option>
                        @foreach($inventory->repository->getSizeVariants() as $sizeVariant)
                            <option value="{{ $sizeVariant->id }}"
                                    purchase="{{ f_currency($sizeVariant->purchase_price) }}"
                                    sales="{{ f_currency($sizeVariant->sales_price) }}"
                                    stock="{{ $sizeVariant->stock }}"
                                    available="{{ $sizeVariant->available_stock }}"
                                    used="{{ $sizeVariant->used_stock }}"
                                    edit="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $sizeVariant,]) }}"
                                    delete="{{ route('merchandise.inventory.delete', ['merchandise' => $merchandise, 'inventory' => $sizeVariant,]) }}"
                            >
                                {{ $sizeVariant->size?->name ?? 'No Size' }} ({{ f_currency($sizeVariant->sales_price) }})
                            </option>
                        @endforeach
                    </select>
                    <hr class="splitter">
                    <div>
                        Purchase: <span class="purchase">{{ f_currency($inventory->purchase_price) }}</span> | Sales: <span class="sales">{{ f_currency($inventory->sales_price) }}</span>
                    </div>
                    <hr class="splitter">
                    <div>
                        Stock: <span class="stock">{{ $inventory->stock }}</span> | Sold: <span class="used">{{ $inventory->used_stock }}</span> | Available: <span class="available">{{ $inventory->available_stock }}</span>
                    </div>
                    <hr class="splitter">
                    <div>
                        <a href="{{ route('merchandise.inventory.edit', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}" class="btn btn-success edit-btn">
                            <i class="icon-note"></i>
                            Edit Inventory
                        </a>
                        <a href="javascript:$('#inventory-{{ $inventory->id }}-delete').submit();" class="btn btn-danger delete-btn">
                            <i class="icon-trash"></i>
                            Delete Inventory
                        </a>
                        <form id="inventory-{{ $inventory->id }}-delete" class="delete-link"
                              action="{{ route('merchandise.inventory.delete', ['merchandise' => $merchandise, 'inventory' => $inventory,]) }}" method="POST"
                              style="display: none;">{{ csrf_field() }}</form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
