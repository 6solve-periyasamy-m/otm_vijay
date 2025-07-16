@extends('layout.customer')

@section('title', __('Reset Password'))

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>
                <p></p>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ $action ?? route('customer.password.email') }}">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
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
                        <div class="kpt_login_title">
                            <h4>Reset Password</h4>
                        </div>
                        <div class="login_form">
                             @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form action="{{ $action ?? route('customer.password.email') }}" method="POST">
                                @csrf
                                <p class="email"><input id="email"  type="email" class="@error('email') is-invalid @enderror" name="email" placeholder="EMAIL ADDRESS" value="{{ old('email') }}" required autocomplete="email" autofocus></p>
                                <p class="submit_btn"><button type="submit">{{ __('Send Password Reset Link') }}</button></p>
                            </form>

                            <p class="forgot_pwd"><a href="{{ route('customer.login') }}">Back to login</a></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
