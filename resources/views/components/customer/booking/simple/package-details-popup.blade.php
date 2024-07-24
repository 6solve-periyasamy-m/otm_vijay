@php /** @var \App\Models\Tour\Tour $tour */ @endphp
<div class="see-more-popup">
    <div class="popup-inner-two">
        <div class="convco-two">
            <div class="whole-block-two">
                <div class="full-top-blcls-two">
                    <h3> Package details </h3>
                    <div class="upp-block-two">
                        <div class="snd-sec">
                            @if(isset($tour->event?->image_url))
                                <div class="left-col">
                                    <img src="{{ asset($tour->event?->image_url) }}" alt="featured-img">
                                </div>
                            @endif
                            <div class="right-col">
                                <h4>{{ $tour->event?->name }}</h4>
                                <h6>{{ $tour->name }}</h6>
                                <!--<p class="location"></p> TODO: Implement Location on Event -->
                                <p class="date">{{ $tour->date_from->format('M d, Y') }} - {{ $tour->date_to->format('M d, Y') }}</p>
                                @foreach($tour->repository->getInclusions() as $inclusion)
                                    <p class="inclusion">{{ $inclusion }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding: 2rem 1rem">
                    {!! $tour->description !!}
                </div>
            </div>
            <div class="close-btn">
                <img src="{{asset('images/booking/close-white-arrow.png')}}" alt="close-btn">
            </div>
        </div>
    </div>
</div>
