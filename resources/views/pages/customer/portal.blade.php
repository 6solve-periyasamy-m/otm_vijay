@extends('layout.customer', ['overflow' => false,])

@section('title', 'Customer Portal')

@section('content')
<div style="position: relative;">
    <div class="d-flex justify-content-center align-items-center h-75 mt-4">
        <div class="donut-menu">
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="donut-center">
            </div>
        </div>
        <div class="click-menu">
            <div class="menu-item" onclick="window.location = '{{ route('customer.edit') }}'">
                <div class="menu-text">
                    <span class="icon-user menu-icon"></span>
                    <span>Your Details</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.finances') }}'">
                <div class="menu-text">
                    <span class="icon-credit-card menu-icon"></span>
                    <span>Your Finance</span>
                </div>
            </div>
            <div class="menu-item">
                <div class="menu-text">
                    <span class="icon-diamond menu-icon"></span>
                    <span>Your Extras</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.itinerary') }}'">
                <div class="menu-text">
                    <span class="icon-globe menu-icon"></span>
                    <span>Your Tours</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('.donut-menu .menu-item').click(function() {
            alert();
        })
    });
</script>
@endsection
