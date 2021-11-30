@extends('layout.customer')

@section('title', '')

@section('content')

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
        <div class="menu-item">
            <div class="menu-text">
                <span class="icon-user menu-icon"></span>
                <span>Your Detail</span>
            </div>
            <ul class="sub-menu">
                <li class="menu-link hide"></li>
                <li class="menu-link">
                    <a href="">Update Details</a>
                </li>                
                <li class="menu-link">
                    <a href="">View ATOL</a>
                </li>                
            </ul>
        </div>
        <div class="menu-item">
            <div class="menu-text">
                <span class="icon-credit-card menu-icon"></span>
                <span>Your Finance</span>
            </div>
            <ul class="sub-menu">
                <li class="menu-link hide"></li>
                <li class="menu-link">
                    <a href="">View Balance</a>
                </li>
                <li class="menu-link">
                    <a href="">Make Payments</a>
                </li>
                <li class="menu-link">
                    <a href="">View Invoice</a>
                </li>               
            </ul>
        </div>
        <div class="menu-item">
            <div class="menu-text">
                <span class="icon-settings menu-icon"></span>
                <span>Extra</span>
            </div>
            <ul class="sub-menu">
                <li class="menu-link hide"></li>
                <li class="menu-link">
                    <a href="">Add-ons</a>
                </li>                
                <li class="menu-link">
                    <a href="">Upgrades</a>
                </li>                
            </ul>
        </div>
        <div class="menu-item">
            <div class="menu-text">
                <span class="icon-globe menu-icon"></span>
                <span>Your Tour</span>
            </div>
            <ul class="sub-menu">
                <li class="menu-link">
                    <a href="" tooltip="Activities"><span class="icon-settings menu-icon"></a>
                </li>
                <li class="menu-link">
                    <a href=""><span class="icon-home menu-icon"></span></a>
                </li> 
                <li class="menu-link">
                    <a href=""><span class="icon-plane menu-icon"></a>
                </li> 
                <li class="menu-link">
                    <a href=""><span class="icon-directions menu-icon"></a>
                </li> 
                <li class="menu-link">
                    <a href=""><span class="icon-tag menu-icon"></a>
                </li>                
            </ul>
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
