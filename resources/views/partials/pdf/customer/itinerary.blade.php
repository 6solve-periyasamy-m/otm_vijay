@php
    /**
     * @var \App\Repository\Abstracts\OrderComponentRepository $orderComponent
     */
    $component = $orderComponent->getAbstractOrderComponent();
@endphp
<div class="card">
    <div class="component-header">
        <div class="width-100 my-auto px-2 py-3 item-name">
            {!! $component->name !!}
        </div>
    </div>
    <div class="component-text">
        <div class="mx-2 row">
            @include('partials.pdf.customer.attributes', ['attributes' => $component->attributes])
            <div class="py-2">
                @if($component->image !== null)
                    <div class="width-1-9 inline-block border-right">
                        <img src="{{ asset($component->image) }}" class="booking-image"/>
                    </div>
                    <div class="inline-block width-8-9 ml-1">
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
                    <div class="inline-block w-100 ml-1">
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
</div>
