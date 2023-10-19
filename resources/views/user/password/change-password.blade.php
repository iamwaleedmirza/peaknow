@extends('user.dashboard.dashboard')

@section('title')
    Change Password
@endsection

@section('css')
    <style>
        .password-field-icon2 {
            float: right;
            margin-left: -44px;
            margin-top: 22px;
            right: 22px;
            position: absolute;
            z-index: 2;
            cursor: pointer;
        }
        .error{
            text-align: start !important;
        }
    </style>
@endsection

@section('content')
    <section class="section-login text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 mw-450px">

                <div class="mb-4">
                    <h4 class="h-color">Change Password</h4>
                </div>

                <form id="change-pass-form" class="mb-4" method="POST"
                    action="{{ route('user.change-password.post') }}"
                    data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"
                    data-parsley-trigger="keyup" data-parsley-validat>
                    @csrf
                    @if (Auth::check())
                        @if (Auth::user()->password !== null)
                        <div class="row mb-3 password-group">
                            <input id="current-password" type="password"
                                class="input-field input-primary @error('current-password') is-invalid @enderror"
                                name="current_password" autocomplete="new-password" placeholder="Current password" required
                                data-parsley-required="true" data-parsley-trigger="change"
                                data-parsley-required-message="Current Password is required.">
                            <span toggle="#current-password" class="fa fa-fw fa-eye password-field-icon2 toggle-password"></span>
                            @error('current-password')
                                <span class="invalid-feedback text-start" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @endif
                    @endif


                    <div class="row mb-3 password-group">
                        <input id="password" type="password"
                            class="input-field input-primary @error('password') is-invalid @enderror" name="password"
                            autocomplete="new-password" placeholder="New Password" required data-parsley-required="true"
                            data-parsley-trigger="change" data-parsley-required-message="Password is required.">
                        <span toggle="#password" class="fa fa-fw fa-eye password-field-icon2 toggle-password"></span>
                        @error('password')
                            <span class="invalid-feedback text-start" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row mb-5 password-group">
                        <input id="password-confirm" type="password" class="input-field input-primary"
                            name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password"
                            data-parsley-required="true" data-parsley-equalto="#password" data-parsley-trigger="change"
                            data-parsley-required-message="Confirm password is required.">
                        <span toggle="#password-confirm"
                            class="fa fa-fw fa-eye password-field-icon2 toggle-password"></span>
                    </div>

                    <div class="form-group mb-5">
                        <button type="submit" class="btn btn-peaks">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script>
        $.validator.addMethod("strong_password", function(value, element) {

            return /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[~!@#$%^&*+=?_-])(?=\S+$).{8,20}$/.test(value);

        }, function(value, element) {
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
        $('#change-pass-form').validate({ // initialize the plugin
            rules: {
                current_password: {
                    required: true,
                },
                password: {
                    required: true,
                    strong_password:true
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                    strong_password:true
                }
            },
            messages: {
                current_password: {
                    required: "Current password is required."
                },
                password: {
                    required: "New password is required."
                },
                password_confirmation: {
                    required: "Confirmation password is required."
                },
            }
        });

        $(document).on("click", ".toggle-password", function() {
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

        $(document).ready(function() {
            @if(session()->has('success'))
            showToast('success', '{{ session()->get('success') }}');
            @endif
            @if(session()->has('warning'))
            showToast('warning', '{{ session()->get('warning') }}');
            @endif
        });
    </script>
@endsection
