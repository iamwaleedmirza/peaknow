@extends('user.base.main')

@section('title') Admin Login @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 col-md-6 col-xl-4 text-center">
                <div class="mb-4">
                    <h2 class="h-color">Admin Login</h2>
                </div>
                        
                        @error('email')
                        <div class="alert alert-danger text-center">
                             {{ $message }} 
                        </div>
                        @enderror
                        @error('password')
                        <div class="alert alert-danger text-center">
                             {{ $message }} 
                        </div>
                        @enderror
                <form class="login-form" method="POST" action="{{ route('admin.login') }}" id="registration_form">
                    @csrf

                    <div class="form-group mb-3">
                        <input id="email" type="email"
                               class="input-field input-primary @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" placeholder="E-mail" required
                               autocomplete="email"
                               autofocus>

                        
                    </div>
                    <div class="form-group mb-4 password-group">
                        <input id="password" type="password"
                               class="input-field input-primary @error('password') is-invalid @enderror" name="password"
                               placeholder="Password" required autocomplete="current-password">
                        <span toggle="#password" class="fa fa-fw fa-eye password-field-icon toggle-password"></span>
                      
                    </div>
                    @if(env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
                    <div class="form-group">
                        <center>
                            <div class="g-recaptcha" id="capcha" data-callback="onloadCallback"></div>
                            <p style="color: red;display: none" id="captcha-error">{{__('Captcha is required')}}</p>
                        </center>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-peaks mt-3">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>
        $(document).on("click",".toggle-password", function() {
            let input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $(this).addClass("fa-eye-slash");
                $(this).removeClass("fa-eye");
            } else {
                input.attr("type", "password");
                $(this).addClass("fa-eye");
                $(this).removeClass("fa-eye-slash");
            }
        });
    </script>

@endsection
