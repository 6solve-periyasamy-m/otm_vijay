<x-admin.section.card>
    <div class="row">
        <div class="col-xl-12 d-flex justify-content-center">
            <img src="{{ $brand->image }}" alt="company logo" width="200" />
        </div>
        <div class="col-6">
            <div>
                <span>Brand Name</span>
                <h6 class="fw-bold">{{ $brand->name }}</h6>
            </div>
            <div class="col-12">
                <span>Brand Email</span>
                <h6 class="fw-bold">{{ $brand->email }}</h6>
            </div>
            <div class="col-12">
                <span>Brand Phone</span>
                <h6 class="fw-bold">{{ $brand->phone }}</h6>
            </div>
            <div>
                <span>Brand Links</span>
                <br />
                @empty($brand->url)
                    <span class="btn btn-outline-dark btn-sm mb-1">
                        {{ Icon::website() }}
                    </span>
                @else
                    <a href="{{ $brand->url }}" class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::website() }}
                    </a>
                @endif
                @empty($brand->facebook)
                    <span class="btn btn-outline-dark btn-sm mb-1">
                        {{ Icon::facebook() }}
                    </span>
                @else
                    <a href="{{ $brand->facebook }}" class="btn btn-outline-dark color-facebook btn-sm mb-1" target="_blank">
                        {{ Icon::facebook() }}
                    </a>
                @endif
                @empty($brand->twitter)
                    <span class="btn btn-outline-dark btn-sm mb-1">
                        {{ Icon::twitter() }}
                    </span>
                @else
                    <a href="{{ $brand->twitter }}" class="btn btn-outline-dark color-twitter btn-sm mb-1" target="_blank">
                        {{ Icon::twitter() }}
                    </a>
                @endif
                @empty($brand->instagram)
                    <span class="btn btn-outline-dark btn-sm mb-1">
                        {{ Icon::instagram() }}
                    </span>
                @else
                    <a href="{{ $brand->instagram }}" class="btn btn-outline-dark color-instagram btn-sm mb-1" target="_blank">
                        {{ Icon::instagram() }}
                    </a>
                @endif
            </div>
        </div>
        <div class="col-6">
            <span>Brand Address</span><br />
            <span class="fw-bold">{{ $brand->active_address->address_line_1 }}</span><br />
            <span class="fw-bold">{{ $brand->active_address->address_line_2 }}</span><br />
            <span class="fw-bold">{{ $brand->active_address->town }}</span><br />
            <span class="fw-bold">{{ $brand->active_address->region }}</span><br />
            <span class="fw-bold">{{ $brand->active_address->country?->name }}</span><br />
            <span class="fw-bold">{{ $brand->active_address->postcode }}</span>
            @if($brand->id !== null)
                <div>
                    <button wire:click='$emit("openModal", "admin.system.brand.form", {{ json_encode(['brand' => $brand->id,]) }})' class="btn btn-outline-success btn-sm mb-1">
                        {{ Icon::edit() }}
                    </button>
                    <button wire:click='delete' class="btn btn-outline-danger btn-sm mb-1">
                        {{ Icon::delete() }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-admin.section.card>
