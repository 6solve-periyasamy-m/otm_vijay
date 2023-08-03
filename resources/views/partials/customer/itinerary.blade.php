@php
    /**
     * @var \App\Repository\Abstracts\OrderComponentRepository $orderComponent
     */
    $component = $orderComponent->getAbstractOrderComponent();
@endphp
<div class="card mx-1">
    <div class="card-body">
        <div class="component-header row">
            <div class="col-12 my-auto d-flex">
                <div class="px-2">
                    {{ $component->icon }}
                </div>
                {!! $component->name !!}
            </div>
        </div>
        <div class="component-text">
            <div class="mx-2 row">
                @include('partials.customer.booking.component.attributes', ['attributes' => $component->attributes])
                @if($component->image !== null)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-6 border-right">
                        <img src="{{ asset($component->image) }}" class="booking-image"/>
                    </div>
                    <div class="col-xl-10 col-lg-9 col-md-8 col-6">
                        <div>
                            <span class="fw-bold">Description<br /></span>
                            {{ $component->description }}
                        </div>
                        @if($component->notes)
                        <div class="border-top mt-1">
                            <span class="fw-bold">Notes<br /></span>
                            {{ $component->notes }}
                        </div>
                        @endif
                    </div>
                @else
                    {{ $component->description }}
                @endif
            </div>
        </div>
    </div>
</div>
