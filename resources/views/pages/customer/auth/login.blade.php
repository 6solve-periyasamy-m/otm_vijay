@extends('layout.customer')

@section('title', __('Login'))

@section('content')
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <p></p>
                    <div class="card-body">
                        <form method="POST" action="{{ $action ?? route('customer.confirm-login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    <a class="btn btn-link" href="{{ route('customer.password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container">
        <div class="body_content">
            <!-- Page Content -->
            <div class="login_inner_content">
                <div class="login_row">
                    <div class="login_img_column">
                        <img src="/images/customer/images/login_page_img.png" alt="Login Image" />
                    </div>
                    <div class="login_form_column">
                        <div class="kpt_logo">
                            <img src="/images/customer/images/KeithProwse_Logo_RGB.svg" alt="KPT Logo" />
                        </div>
                        <div class="kpt_login_title">
                            <h3>The experience of a lifetime.<br /> Every time.</h3>
                            <h4>Login to your Keith Prowse Travel account</h4>
                        </div>
                        <div class="login_form">
                            <form action="{{ $action ?? route('customer.confirm-login') }}" method="POST">
                                @csrf

                                <p class="email"><input id="email"  type="email" class="@error('email') is-invalid @enderror" name="email" placeholder="EMAIL ADDRESS" value="{{ old('email') }}" required autocomplete="email" autofocus></p>
                                <p class="password"><input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" placeholder="PASSWORD" required autocomplete="current-password"></p>
                                <P class="remember_me">
                                    <span class="remeber_me">
                                        <label for="remember_me_check" class="">
                                            <input id="remember_me_check" class="hs-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span>Remember me</span>
                                        </label>
                                    </span>
                                </P>
                                <p class="submit_btn"><button type="submit"><span>SUBMIT <img src="{{ asset('/images/customer/images/arrow_right_white.svg') }}" class="pay_right_arrow_wht" />
                                <img src="{{ asset('images/customer/images/arrow_right.svg') }}" class="pay_right_arrow_org" /></span></button></p>
                            </form>
                            <p class="forgot_pwd"><a href="{{ route('customer.password.request') }}">FORGOT PASSWORD?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
