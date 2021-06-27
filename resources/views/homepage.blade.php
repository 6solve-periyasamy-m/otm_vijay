@extends ('layout.main')
@section('content')
<div class="container-fluid homepage" id="app">
    <div class="menucontainer">
        <div class="menucontainer__contact">
            <div class="row">
                <div class="col">
                    <a href="mailto:Info@OctopusTravelMatrix.com" class="contactbar__menu">✉</a>
                </div>
                <div class="col">
                    <a href="tel:07912573576" class="contactbar__menu">✆</a>
                </div>
            </div>
        </div>
    </div>

    <div class="logocontainer">
        <div class="container">
            <div class="row">
                <div class="col-1">
                </div>
                <div class="col">
                    <h1 class="fader f1" onload="this.style.opacity='0'">Octopus</h1>
                </div>
                <div class="col-6">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col">
                    <h1 class="fader f2" onload="this.style.opacity='0'">Travel</h1>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                </div>
                <div class="col">
                    <h1 class="fader f3" onload="this.style.opacity='0'">Matrix</h1>
                </div>
                <div class="col-1">
                </div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col">
                    <p class="fader f3 logotext">Octopus Travel Matrix offers travel agents a complete tour booking
                        solution. Coded to ease the process of booking, OTM provides everything you need to get
                        customers prepared for
                        your services</p>
                </div>
                <div class="col-1"></div>
            </div>
        </div>
    </div>

    <div class="gradient1"></div>

    <div class="testimonials">
        <h1 class="headertext">Testimonials</h1>
        <div class="testimonial">
            <div class="container">
                <div class="row">
                    <img src="/images/exampleavatar.jpg" class="avatarimg img-fluid">
                </div>
                <div class="row testimonial__text">
                    <h3>"Octopus travel matrix has made my company a lot of money. I would go as far to say that I
                        love OTM!"<h3>
                </div>
                <div class="row">
                    <p class="testimonial__author">Joe bloggs - example company </p>
                </div>
            </div>
        </div>
        <div class="testimonial">
            <div class="container">
                <div class="row">
                    <img src="/images/exampleavatar3.jpg" class="avatarimg img-fluid">
                </div>
                <div class="row testimonial__text">
                    <h3>"Wow, OTM is exactly what we were looking for! Their dedicated team ensure your prepared for
                        any situation that may arise during booking"<h3>
                </div>
                <div class="row testimonial__author">
                    <p>Sarah site - Company ltd </p>
                </div>
            </div>
        </div>
        <div class="testimonial">
            <div class="container">
                <div class="row">
                    <img src="/images/exampleavatar2.jpg" class="avatarimg img-fluid">
                </div>
                <div class="row testimonial__text">
                    <h3>"Wow, OTM is exactly what we were looking for! Their dedicated team ensure your prepared for
                        any situation that may arise during booking"<h3>
                </div>
                <div class="row testimonial__author">
                    <p>Sarah smith - Alternate company </p>
                </div>
            </div>
        </div>
    </div>

    <div class="gradient2"></div>

    <div class="news">
        <h1 class="headertext alt">News</h1>
        <div class="patchnotes">
            <div class="patchnotes__note">
                <h3>Patch 0.1 - Prerelease</h3>
                <p>Hello users! We've been working hard on delivering OTM as the best travel booking solution.
                    The setup is easy to use and navigate, so dive right in after reading the documentation
                    section.</br>
                    -New homepage</br>
                    -Validation on booking form</br>
                    -Removed buggy material design fields</br>
                    -Changed DB field sizes
                </p>
            </div>
            <div class="patchnotes__note">
                <h3>Patch 0.0 - Welcome to OTM</h3>
                <p>Welcome to OTM!
                </p>
            </div>
        </div>
    </div>

    <div class="gradient1"></div>

    <div class="video">
        <h1 class="headertext">Videos</h1>
        <div class="videocontainer">
            <iframe class="videoframe" src="https://www.youtube.com/embed/yqWX86uT5jM">
            </iframe>
        </div>
    </div>

    <div class="gradient2"></div>

    <div class="documentation">
        <h1 class="headertext alt">Documentation</h1>
        <div class="documentation_text">
            <p>Octopus Travel Matrix offers the travel agency a complete tour solution.</p>
            <p>OTM is a Powered web application, meaning it can be used on a wide varity of devices.
                This means more customers than ever can access your tours increasing profit and user satisfaction</p>
            <p>System requirements: </br>
                -N/a </br>
                -N/a </br>
                -N/a </br>
                -N/a </br>
                <p>
                    - Your site can be found at www.travelagency.info. </br>
                    - Customer bookings has a dedicated URL at travelagency.octopustravelmatrix.com/booking</br>
                    - you can setup a redirect from your site to your OTM booking form. </br>
                    - Customer booking Form is a public URL from your site to your OTM site. </br>
                </p>
                <p>This is a prerelease build. Please contact one of our sales representitives to arrange a guided
                    walkthrough of our systems.

        </div>
    </div>
</div>
@endsection
