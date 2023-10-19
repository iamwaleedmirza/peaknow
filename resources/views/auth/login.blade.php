@extends('user.base.main')

@section('title') Log In @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="section">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5 col-xl-4">

                <div class="text-center mb-4">
                    <h2 class="h-color text-uppercase">Sign In</h2>
                </div>

                @if(session()->has('error'))
                    <div class="alert alert-danger text-center">
                        {{ session()->get('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-center mb-1">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{$error}}</p>
                        @endforeach
                    </div>
                    <br>
                @endif
                <form class="login-form" method="POST" action="{{ route('user.login') }}" id="registration_form">
                    @csrf

                    <div class="form-group mb-3">
                        <input id="email" type="email"
                               class="input-field input-primary"
                               name="email" value="{{ old('email') }}" placeholder="E-mail" data-parsley-required="true"
                               data-parsley-trigger="change" data-parsley-required-message="Email is required."
                               autofocus>
                    </div>
                    <div class="form-group mb-4 password-group">
                        <input id="password" type="password"
                               class="input-field input-primary" name="password"
                               placeholder="Password" data-parsley-required="true" data-parsley-trigger="change"
                               data-parsley-required-message="Password is required.">
                        <span data-toggle="#password" class="fa fa-fw fa-eye password-field-icon toggle-password"></span>
                    </div>
                    @if(env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
                        <div class="form-group">
                            <div class="d-flex flex-column align-items-center">
                                {!!  GoogleReCaptchaV3::renderField('user_login_id', 'user_login') !!}
                            </div>
                        </div>
                    @endif
                    <div class="text-center">
                    <button type="submit" class="btn btn-peaks mt-3">
                        Sign in
                    </button>
                    </div>
                </form>
                <div class="text-center mb-3">
                    <a class="forgot-pass-text" href="{{ route('password-reset') }}">
                        Forgot your password?
                    </a>
                </div>

                {{--<div class="text-center mb-5">
                    <p class="t-color">
                        Login with your social media accounts
                    </p>
                    <div class="d-flex flex-row justify-content-center">
                        <a href="{{ route('social-redirect','google') }}" class="social-icon">
                            <i class="fab fa-google s-icon"></i>
                        </a>
                        <a href="{{ route('social-redirect','facebook') }}" class="social-icon">
                            <i class="fab fa-facebook s-icon"></i>
                        </a>
                    </div>
                </div>
                --}}
                <div class="row text-center pb-5 fs-3">
                <span class="t-color">
                    New here?
                    <a class="t-link" href="{{ route('register.user') }}">Sign Up Now</a>
                </span>
                </div>
            </div>
        </div>
    </section>

    @include('auth.set-social-email')

@endsection
@section('js')
    {!!  GoogleReCaptchaV3::init() !!}
    <script>
         setInterval(() => {
            refreshReCaptchaV3('user_login_id','user_login');
        }, 120000);
    </script>
    <script>
        @if(session()->has('success'))
            showAlert('success', '{{ session()->get('success') }}');
        @endif

        $('#registration_form').parsley().on('field:validated', function () {
            const ok = $('.parsley-error').length === 0;
        }).on('field:submit', function () {
            console.log('submit form');
            return false;
        });

        @if ( Session::get('email-required') == 1 )
        setTimeout(function () {
                $('#modalSetEmail').modal('show');
            }, 0);
        @endif
    </script>

@endsection
