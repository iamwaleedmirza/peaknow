@extends('user.base.main')
@section('title')
    Register
@endsection
@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">

    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
    <style>
          #agreement-error { display: inline;  }
          .form-check-input { width: 1em !important; height: 1em !important;  }
    </style>
@endsection
@section('content')
    <section class="section-login">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5">

                <div class="text-center mb-4">
                    <h2 class="h-color">Create an account</h2>
                </div>

                <form class="mb-4" method="POST" action="{{ route('user.register') }}" id="registration_form">
                    @csrf

                    <div class="row mb-3">

                        <div class="col-md-6 mb-3 mb-md-0">

                            <input id="first_name" type="text"
                                class="input-field input-primary @error('first_name') is-invalid @enderror" name="first_name"
                                value="{{ Session::get('user') !== null ? Session::get('user')['first_name'] : old('first_name') }}"
                                placeholder="First name" data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                data-parsley-trigger="change" data-parsley-required-message="First Name is required.">

                            @error('first_name')
                                <span class="invalid-feedback text-start" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-6">

                            <input id="last_name" type="text"
                                class="input-field input-primary @error('last_name') is-invalid @enderror" name="last_name"
                                value="{{ Session::get('user') !== null ? Session::get('user')['last_name'] : old('last_name') }}"
                                data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                data-parsley-trigger="change" data-parsley-required-message="Last Name is required."
                                placeholder="Last name">

                            @error('last_name')
                                <span class="invalid-feedback text-start" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    <div class="form-group mb-3">

                        <input id="email" type="email"
                            class="input-field input-primary @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" data-parsley-required="true" autocomplete="false"
                            data-parsley-type="email" data-parsley-trigger="change"
                            data-parsley-required-message="Email is required." placeholder="E-mail">

                        @error('email')
                            <span class="invalid-feedback text-start" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="form-group mb-3">

                        <input id="phone" type="tel" class="input-field input-primary  @error('phone') is-invalid @enderror"
                            value="{{ old('full_number') }}" name="phone" data-parsley-required="true"
                            data-parsley-trigger="change" data-parsley-required-message="Phone is required."
                            placeholder="Phone number (xxx) xxx-xxxx" data-mask="(000) 000-0000">

                        {{-- <span id="valid-msg" class="hide  text-start">✓ Valid</span> --}}
                        <span id="error-msg" class="hide text-start"></span>



                    </div>
                    @error('phone')
                        <span class="invalid-feedback text-start" role="alert" style="display:block; position:relative; top: -15px;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="form-group mb-3 password-group">

                        <input id="password" type="password"
                            class="input-field input-primary @error('password') is-invalid @enderror" name="password"
                            placeholder="Password" autocapitalize="off" autocomplete="off" data-parsley-required="true"
                            data-parsley-trigger="change" data-parsley-minlength="8"
                            data-parsley-required-message="Password is required.">

                        <span data-toggle="#password" class="fa fa-fw fa-eye password-field-icon toggle-password"></span>

                        @error('password')
                            <span class="invalid-feedback text-start" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class="form-group mb-3 password-group">

                        <input id="password-confirm" type="password" class="input-field input-primary"
                            name="password_confirmation" placeholder="Confirm Password" autocapitalize="off"
                            autocomplete="off" data-parsley-required="true" data-parsley-equalto="#password"
                            data-parsley-trigger="change" data-parsley-required-message="Confirm password is required.">

                        <span data-toggle="#password-confirm" class="fa fa-fw fa-eye password-field-icon toggle-password">
                        </span>

                    </div>

                    <div class="terms-check-box">
                        <div class="form-check checkbox-layout">

                            <input class="form-check-input" type="checkbox" value="1" name="agreement" id="flexCheckDefault"
                                data-parsley-required="true" data-parsley-trigger="change"
                                data-parsley-required-message="You must agree with our terms and conditions to continue.">

                            <label class="form-check-label t-color text-start agreementLabel" for="flexCheckDefault">
                                I agree to
                                <a href="{{env('WP_URL')}}/telehealth-consent" class="t-link" target="_blank">Telemedicine Consent</a>,
                                <a href="{{env('WP_URL')}}/refund-policy" class="t-link" target="_blank">Refund Policy</a>,
                                <a href="{{env('WP_URL')}}/privacy-policy" class="t-link" target="_blank">Privacy Policy</a>,
                                <a href="{{env('WP_URL')}}/terms-and-conditions" class="t-link" target="_blank">Terms & Conditions</a>,
                                <a href="https://customerconsents.s3.amazonaws.com/Beluga_Health_Telemedicine_Informed_Consent.pdf" class="t-link" target="_blank">Beluga Informed Consent</a> and
                                <a href="https://customerconsents.s3.amazonaws.com/Beluga_Health_PA_Privacy_Policy.pdf" class="t-link" target="_blank">Beluga Privacy Policy</a>.
                            </label>

                            @error('agreement')
                                <span class="invalid-feedback text-start" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>

                    @error('captcha')
                        <div class="alert alert-danger text-center">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
                        <div class="form-group">
                            <div class="d-flex flex-column align-items-center">
                                {!! GoogleReCaptchaV3::renderField('user_register_id', 'user_register') !!}
                            </div>
                        </div>
                        <br>
                    @endif

                    <div class="form-group text-center mb-5">
                        <button type="submit" id="registration_form_btn" class="btn btn-peaks loaderBtn submitBTN">
                            Next
                        </button>
                    </div>

                </form>

                {{--<div class="text-center mb-5">
                    <p class="t-color">
                        Sign Up with your social media accounts
                    </p>
                    <div class="d-flex flex-row justify-content-center">
                        <a href="{{ route('social-redirect', 'google') }}" class="social-icon">
                            <i class="fab fa-google s-icon"></i>
                        </a>
                        <a href="{{ route('social-redirect', 'facebook') }}" class="social-icon">
                            <i class="fab fa-facebook s-icon"></i>
                        </a>
                    </div>
                </div>--}}

                <div class="form-group text-center pb-5">
                    <span class="t-color">
                        Already have an account?
                        <a class="t-link" href="{{ route('login.user') }}"> Sign in</a>
                    </span>
                </div>
            </div>
        </div>
    </section>

    @include('auth.set-social-email')
    @include('auth.modals.verify-email')
    @include('auth.modals.verify-otp')
@endsection
@section('js')
    {!! GoogleReCaptchaV3::init() !!}
    <script>
        setInterval(() => {
           refreshReCaptchaV3('user_register_id', 'user_register');
       }, 120000);
   </script>
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
    $( document ).ready( function () {
        $('#registration_form').validate({ // initialize the plugin
            rules: {
                email: {
                    required: true,
                    email: true
                },
                first_name: {
                    required: true,
                    minlength: 3
                },
                last_name: {
                    required: true,
                    minlength: 3
                },
                phone: {
                    required: true
                },
                agreement: {
                    required: true
                },
                password: {
                    required: true,
                    strong_password: true
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                    strong_password: true
                }
            },
            messages: {
                email: {
                    required: "Email is required."
                },
                first_name: {
                    required: "First Name is required."
                },
                last_name: {
                    required: "Last Name is required."
                },
                phone: {
                    required: "Phone is required."
                },
                agreement: {
                    required: "You must agree with our terms and conditions to continue."
                },
                password: {
                    required: "Password is required."
                },
                password_confirmation: {
                    required: "Confirmation password is required."
                }
            },
				errorPlacement: function ( error, element ) {
					// Add the `invalid-feedback` class to the error element
					error.addClass( "invalid-feedback" );
					if ( element.attr("name") == "agreement" ) {
						error.insertAfter($('.agreementLabel') );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					//$( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
				},
				unhighlight: function (element, errorClass, validClass) {
					//$( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
				}
        });
    });
    </script>
    <script src="{{ asset('/js/bootstrap-pincode-input.js') }}"></script>
    <script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('mask/src/jquery.mask.js') }}"></script>

    <script>
        var input = document.querySelector("#phone");
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        var iti = window.intlTelInput(input, {
            // allowDropdown: false,
            // autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            hiddenInput: "full_number",
            // initialCountry: "auto",
            // localizedCountries: { 'de': 'Deutschland' },
            nationalMode: true,
            onlyCountries: ['us'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
        });



        $(document).on('click', ".iti__active", function() {
            $('.submitBTN').attr('type', 'submit')
            $('.submitBTN').removeAttr('disabled')
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
        });
    </script>
    <script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>
    <script>
        $(document).on('click', ".loaderBtn", function() {

            var checkCountryCode = $('.iti__selected-dial-code');
            if (checkCountryCode.html() == null || checkCountryCode.html() == '') {
                $('.submitBTN').attr('type', 'button')
                $('.submitBTN').attr('disabled', 'true')
                errorMsg.innerHTML = "";
                errorMsg.innerHTML = "Please select country code.";
                input.classList.add("error");
                errorMsg.classList.remove("hide");
                $('.loaderElement').hide();
            }
            var form = $('#registration_form');
            if (form.valid()) {
                var checkCountryCode = $('.iti__selected-dial-code');
                if (checkCountryCode.html() == null || checkCountryCode.html() == '') {
                    $('.submitBTN').attr('type', 'button')
                    $('.submitBTN').attr('disabled', 'true')
                    errorMsg.innerHTML = "";
                    errorMsg.innerHTML = "Please select country code.";
                    input.classList.add("error");
                    errorMsg.classList.remove("hide");
                    $('.loaderElement').hide();
                } else {
                    $('.loaderElement').show();
                }
            }

        });

        @if (Session::get('email-required') == 1)
            setTimeout(function () {
            $('#modalSetEmail').modal('show');
            }, 0);
        @endif
        @if ($message = Session::get('warning'))
            showToast('error', '{{ $message }}');
        @endif
    </script>
@endsection