@extends('layout.master')
@section('title', 'Homepage')
@section('header-script')
<style>
    .page {
        padding: 1rem;
        margin-top: 0.5rem;
        background: white;
        overflow: hidden;
        border: 4px grey solid;
        border-radius: 15px;
        color: #333;
    }
    .definition__heading {
        font-size: 1.5rem;
    }
    .definition__body {
        margin-left: 1rem;
    }
</style>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
            <h6>Home Page for an agency: to be rewritten</h6>
            <em>Octopus Travel Matrix offers the travel agency a complete tour solution.</em>
            <dl class="definition">

                <dt class="definition__heading">Demo site</dt>
                <dd class="definition__body">Your site already exists, in our example it is <strong>www.travelagency.online</strong>.</dd>
                
                <dt class="definition__heading">Customer bookings</dt>
                <dd class="definition__body">
                    <dt class="definition__subheading">Bookings URL<dt>
                        <dd class="definition__body">https://travelagency.octopustravelmatrix.com/booking</dd>
                    <dt class="definition__subheading">Redirect from your site to your OTM booking form</dt>
                        <dd class="definition__body">https://booking.travelagency.online</dd>
                </dd>
                <dt class="definition__heading">Tours</dt>
                <dd class="definition__body">
                    <dt class="definition__subheading">Booking<dt>
                    <dd class="definition__body">Your customers can be given URLs to book specific tours</dd>
                    <dd class="definition__body">https://booking.travelagency.online/booking/world-cup-tour</dd>
                </dd>

                <dt class="definition__heading">
                    <svg width="40px" height="40px" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </dt>
                <dd class="definition__body">
                    <dt class="definition__subheading">Documentation<dt>
                    <dd class="definition__body">
                        <a href="/documentation" class="underline text-gray-900 dark:text-white">User Guide</a>
                    </dd>
                </dd>
                <dt class="definition__heading">Software Tests</dt>
                <dd class="definition__body">
                    <dt class="definition__subheading">Examples (open in test tab)<dt>
                    <dd class="definition__body"><a href="/booking/world-cup">Booking Form</a> tour for specific 'world-cup' event</dd>
                    <dd class="definition__body"><a href="/booking/vuetest">Frontend tests</a></dd>
                    <hr/>
                    <div class="ml-8 small">
                        <h4>Alternative paths: deprecated</h4>
                        <p>Booking forms are only to be retrieved for actual tours using /booking/tour-name, alternative paths
                            to a booking form could involve selection dropdowns for the tour/event which have been disabled</p>
                        <dd class="definition__body"><a target="newtab" href="/booking">Booking Form</a> - customer selects event/tour on the form.</dd>
                        <dd class="definition__body"><a target="newtab" href="/booking/tour/world-cup">Flexible Tour Bookings</a> - specific tour URL to list on your site, or email to customers for a specific tour.</dd>
                        <dd class="definition__body"><a target="newtab" href="/booking/order-number">Easy Tour Booking Form Secure Revisit</a> - a unique single-use URL that allows your customer to securely access their booking.</dd>
                        </div>
                </dd>
            </dl>
        </div>
    </div>

<div class="card">
    <div class="card-body">
    <h4>Sections</h4>
    <div style="display: flex; flex-direction: column">
        <div>
            <svg width="40px" height="40px" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <a href="/video" class="underline text-gray-900 dark:text-white">
                Resources
            </a>
        </div>

        <div>
            <p>OTM manages all aspects of the tour including accommodation, airlines, travel, events and activities.  Here are some resources to help you learn how to use it.</p>
            <ul>
                <li>Video</li>
                <li>Podcasts</li>
            </ul>
        </div>

        <div>
            <svg width="40px" height="40px" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
            <a href="/news" class="underline text-gray-900 dark:text-white">News</a>
        </div>

        <div>
            <svg width="40px" height="40px" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Travel Operator Testimonials
        </div>
    </div>

    <div class="flex items-center">

        <a href="">
            <svg width="40px" height="40px" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="-mt-px w-5 h-5 text-gray-400">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </a>

        <a href="https://laravel.bigcartel.com" class="ml-1 underline">
            Buy Octopus Travel Matrix
        </a>

        <a href="">
            <svg width="40px" height="40px" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="ml-4 -mt-px w-5 h-5 text-gray-400">
                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </a>

        <a href="https://github.com/sponsors/taylorotwell" class="ml-1 underline">
            Like OTM
        </a>
    </div>
    </div>
</div>
@endsection
