@extends('user.base.main')

@section('title') Reset Password @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')

    <section class="section-login text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5">

                <div class="mb-4">
                    <h2 class="h-color">Create New Password</h2>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger text-center mb-1">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{$error}}</p>
                        @endforeach
                    </div>
                    <br>
                @endif
                <form id="form-reset-password" class="mb-4" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="row mb-3 text-start">
                        <label for="email" class="t-color text-start text-uppercase mb-2">
                            {{ __('E-Mail Address') }}
                        </label>
                        <input id="email" type="email" name="email"
                               value="{{ isset($_REQUEST['email'])?$_REQUEST['email'] :old('email') }}"
                               autocomplete="email"
                               class="input-field input-primary @error('email') is-invalid @enderror" required>

                        @error('email')
                        {{-- <span class="invalid-feedback text-start" role="alert">
                            <strong>{{ $message }}</strong>
                        </span> --}}
                        @enderror
                    </div>
                    <div class="row">
                        <label for="password" class="t-color text-start text-uppercase mb-2">
                            {{ __('Password') }}
                        </label>
                    </div>
                    <div class="row mb-3 password-group text-start">
                        <input id="password" type="password"
                               class="input-field input-primary @error('password') is-invalid @enderror" name="password"
                               autocomplete="new-password" required
                               data-parsley-required="true" data-parsley-trigger="change"
                               data-parsley-required-message="Password is required.">

                        <span toggle="#password" class="fa fa-fw fa-eye password-field-icon toggle-password"></span>
                        @error('password')
                        {{-- <span class="invalid-feedback text-start" role="alert">
                            <strong>{{ $message }}</strong>
                        </span> --}}
                        @enderror
                    </div>
                    <div class="row">
                        <label for="password-confirm" class="t-color text-start text-uppercase mb-2">
                            {{ __('Confirm Password') }}
                        </label>
                    </div>
                    <div class="row mb-5 password-group text-start">
                        <input id="password-confirm" type="password" class="input-field input-primary"
                               name="password_confirmation" required autocomplete="new-password">
                        <span toggle="#password-confirm"
                              class="fa fa-fw fa-eye password-field-icon toggle-password"></span>
                    </div>

                    <div class="form-group mb-5">
                        <button type="submit" class="btn btn-peaks">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
@section('js')
    <script>

        $.validator.addMethod("strong_password", function (value, element) {

            return /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[~!@#$%^&*+=?_-])(?=\S+$).{8,20}$/.test(value);

        }, function (value, element) {
            let password = $(element).val();
            if (!(/^(.{8,20}$)/.test(password))) {
                return 'Password must be between 8 characters long.';
            } else if (!(/^(?=.*[A-Z])/.test(password))) {
                return 'Password must contain at least one uppercase.';
            } else if (!(/^(?=.*[a-z])/.test(password))) {
                return 'Password must contain at least one lowercase.';
            } else if (!(/^(?=.*[0-9])/.test(password))) {
                return 'Password must contain at least one digit.';
            } else if (!(/^(?=.*[~!@#$%^&*+=?_-])/.test(password))) {
                return "Password must contain at least one special character.";
            }
            return false;
        });

        $(document).ready(function () {

            $('#form-reset-password').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        strong_password: true
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                    }
                },
                messages: {
                    email: {
                        required: "Email is required.",
                        email: 'Please enter a valid email.'
                    },
                    password: {
                        required: 'Please enter a password.',
                    },
                    password_confirmation: {
                        required: 'Please enter confirm password.',
                        equalTo: 'Confirm password mismatch.',
                    }
                },
            })

        })

        $(document).on("click", ".toggle-password", function () {
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
