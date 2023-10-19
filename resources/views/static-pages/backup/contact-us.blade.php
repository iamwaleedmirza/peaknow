@extends('static-pages.base.main')

@section('title') CONTACT US @endsection

@section('meta_description')
    Get in touch! Filling our online form is easy and discreet. Your information is completely safe and secure in www.peakscurative.com
@endsection
@section('css')
<link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
    <style>

    </style>
@endsection
@section('content')

    <section class="section">
        <div class="section section-contact-us">
            <div class="mb-1 pt-4">
                <div class="section-title">
                    Contact us
                </div>
            </div>
            <div class="row justify-content-center mx-0 px-0">
                <div class="col-10 col-md-6">

                    <p class="text-center mb-5">
                        Leave your information below and our team will contact you.
                    </p>

                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                    @else

                        @if ($errors->any())
                            <div class="alert alert-danger text-center">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form class="contact-form" method="POST" action="{{ route('contact-us-post') }}" id="contact_form">
                            @csrf

                            <x-honeypot/>

                            <div class="mb-3">
                                <input id="first_name" type="text"
                                       class="input-field input-primary"
                                       name="first_name" value="{{ old('first_name') }}" required
                                       autocomplete="first_name" placeholder="First name">
                            </div>

                            <div class="mb-3">
                                <input id="last_name" type="text"
                                       class="input-field input-primary"
                                       name="last_name" value="{{ old('last_name') }}"
                                       required autocomplete="last_name"
                                       placeholder="Last name">
                            </div>

                            <div class="mb-3">
                                <input id="email" type="email"
                                       class="input-field input-primary"
                                       name="email" value="{{ old('email') }}"
                                       placeholder="E-mail" required
                                       autocomplete="email">
                            </div>

                            <div class="mb-3">
                                <input id="phone" type="tel"
                                       class="input-field input-primary"
                                       value="{{ old('phone') }}" name="phone"
                                       required autocomplete="phone"
                                       placeholder="Phone">
                                       <span id="error-msg" class="hide text-start"></span>
                            </div>

                            <div class="mb-3">
                                @php $states = \DB::table('states')->select('state')->get();  @endphp
                                <select class="input-field input-primary" name="state" required>
                                    <option value="">Select your state</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->state}}">{{$state->state}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                            <textarea id="message"
                                      class="input-primary"
                                      name="message" required
                                      rows="4" value="{{ old('message') }}"
                                      placeholder="Message"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="d-flex justify-content-center">
                                    <div>
                                        {!!  GoogleReCaptchaV3::renderField('contact_us_id', 'contact_us') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-peaks-hollow submitBTN text-uppercase mt-3">
                                    Submit
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    {!!  GoogleReCaptchaV3::init() !!}

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
            utilsScript: "{{asset('intl-tel-input/build/js/utils.js')}}",
        });
       
    
    </script>
    <script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#contact_form').validate({ // initialize the plugin
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
                    state: {
                        required: true
                    },
                    message: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    email: {
                        required: "Email is required."
                    },
                    first_name: {
                        required: "First name is required."
                    },
                    last_name: {
                        required: "Last name is required."
                    },
                    phone: {
                        required: "Phone is required."
                    },
                    state: {
                        required: "State is required."
                    },
                    message: {
                        required: "Message is required."
                    }
                },
                submitHandler: function(form) {
                    form.submit()
                    $('.loaderElement').show();
                }
            });
        });
    </script>
@endsection
