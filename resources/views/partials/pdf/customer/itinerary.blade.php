@php
    /**
     * @var \App\Repository\Abstracts\OrderComponentRepository $orderComponent
     */
    $component = $orderComponent->getAbstractOrderComponent();
@endphp
<div class="card">
    <div class="component-header">
        <div class="width-100 my-auto d-flex">
            {!! $component->name !!}
        </div>
    </div>
    <div class="component-text">
        <div class="mx-2 row">
            @include('partials.customer.booking.component.attributes', ['attributes' => $component->attributes])
            @if($component->image !== null)
                <div class="width-1-4 border-right">
                    <img src="{{ asset($component->image) }}" class="booking-image"/>
                </div>
                <div class="width-3-4">
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
                <div>
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
            @endif
        </div>
    </div>
</div>
