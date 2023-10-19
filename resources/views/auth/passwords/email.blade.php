@extends('user.base.main')

@section('title') Reset Password @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')

    <section class="section text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5 col-xl-4">

                <div class="mb-4">
                    <h2 class="h-color">Reset Password</h2>
                    <p>Enter your email address to reset password</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form id="form-forgot-password" class="login-form" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group row mb-3">
                        <label for="email" class="text-start mb-2">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="input-field input-primary @error('email') is-invalid @enderror"
                               autocomplete="email" autofocus placeholder="Enter your email address">

                        @error('email')
                        <span class="invalid-feedback text-start" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                     @if(env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
                        <div class="form-group">
                            <div class="d-flex flex-column align-items-center">
                                {!!  GoogleReCaptchaV3::renderField('user_login_id', 'user_login') !!}
                            </div>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-peaks mt-3">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('js')
{!!  GoogleReCaptchaV3::init() !!}
<script>
         setInterval(() => {
            refreshReCaptchaV3('user_login_id','user_login');
        }, 120000);
    </script>
    <script>

        $(document).ready(function () {

            $('#form-forgot-password').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "Email is required.",
                        email: 'Please enter a valid email.'
                    }
                },
            })

        })

    </script>
@endsection