@extends('layout.customer')

@php
/**
 * @var \App\Models\Order\OrderCustomer $orderCustomer
 */
@endphp

@section('title', 'Your Faqs')

@push('footer-stack')
    <script type="text/javascript">
        
    </script>
@endpush

@section('content')
<div class="inner_content">
    <x-customer.overview-top-bar title="FAQ" :search="false"/>
    <div class="faqs_inner">
        <div class="faqs_list">
            @forelse($faqs as $faq)
                <div class="tab-accordian">
                    <div class="titleWrapper inactive">
                        <h4>{{ $faq->question }}</h4>
                        <div class="arrow-icon">
                            <img src="/images/customer/images/arrow-down-black.svg" alt="arrow_down"/>
                        </div>
                    </div>
                    <div class="desWrapper" style="display:none;">
                        <p>{!! $faq->answer !!}</p>
                    </div>
                </div>
            @empty
                <p>No FAQs found.</p>
            @endforelse
        </div>
    </div>
</div>
<script>
    jQuery('.tab-accordian .titleWrapper').click(function(){
        jQuery(this).toggleClass('active');
        jQuery(this).closest('.tab-accordian').find('.desWrapper').toggle(400);
    })
</script>

@endsection

@section('footer-script')
<script>

</script>
@endsection
