@extends('user.base.main')

@section('title') OTP Verification @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">

    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="section-login text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-11 col-sm-9 col-md-7 col-lg-5 card mw-550px p-3 p-md-4">
                {{-- @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
                        <span>{{ $message }}</span>
                    </div>
                @endif
                @if ($message = Session::get('warning'))
                    <div class="alert alert-danger alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
                        <span>{{ $message }}</span>
                    </div>
                @endif --}}

                <div>
                    <h4 class="h-color">OTP Verification</h4>
                </div>

                <form autocomplete="false" class="mb-4" method="POST" action="{{ route('user.verifyOtpByType') }}"
                      id="otp_form" data-parsley-errors-messages-disabled>
                    @csrf
                    <div class="modal-body">
                        <h6 class="text-center" id="exampleModalLabel">
                            <img src="{{asset('images/svg/otp2.svg')}}" alt="" style="height: 210px;">
                        </h6>
                        <h6 class="text-center my-4" id="exampleModalLabel">Verify Your Mobile Number</h6>
                        <p class="text-center">Please enter the verification code sent to your mobile number

                            <strong>
                             <span class="changedPhone h-color">
                            @if (Auth::check())
                                     {{Session::get('change-phone')!==null? Session::get('change-phone') :Auth::user()->phone}}
                                 @endif
                        </span>
                            </strong>
                        </p>

                        <input type="text" hidden name="type" value="phone">
                        <div class="d-flex form-group justify-content-center text-center px-5" id="phone_pincode">
                            <input autocomplete="false" type="number" name="phoneVerifyPin[1]"
                                   class="pincode-input phone-code col otp-field" required>
                            <input autocomplete="false" type="number" name="phoneVerifyPin[2]"
                                   class="pincode-input phone-code col otp-field" required>
                            <input autocomplete="false" type="number" name="phoneVerifyPin[3]"
                                   class="pincode-input phone-code col otp-field" required>
                            <input autocomplete="false" type="number" name="phoneVerifyPin[4]"
                                   class="pincode-input phone-code col otp-field" required>
                            <input autocomplete="false" type="number" name="phoneVerifyPin[5]"
                                   class="pincode-input phone-code col otp-field" required>
                            <input autocomplete="false" type="number" name="phoneVerifyPin[6]"
                                   class="pincode-input phone-code col otp-field" required>
                        </div>
                        @php
                            $resendCount = \App\Models\UserLogs::where('user_id',Auth::user()->id)->where('type','changeNumber')->orderBy('created_at','DESC')->get();
                        @endphp
                        @if ($resendCount->count() >= 1)

                        @else
                            <p class="my-2 text-center NumberChangeModalBTN">
                                <a href="#NumberChangeModal" class="t-link" data-bs-toggle="modal"
                                   data-bs-target="#NumberChangeModal">
                                    Do you want to change mobile number?
                                </a>

                            </p>
                        @endif

                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <div class="d-flex flex-column align-items-center flex-md-row gap-2">
                            <a href="{{ route('resend.otp') }}">
                                <button type="button" class="btn btn-peaks new-code">Send a new code</button>
                            </a>
                            <button type="button" id="verifyPhoneBtn" class="btn btn-peaks loaderBtn">Next</button>
                        </div>
                    </div>
                    {{-- <div class="row mb-3">
                        <label for="age" class="t-color text-start text-uppercase mb-2">Enter OTP</label>
                        <input id="otp" type="text"
                               class="input-field input-primary  @error('otp') is-invalid @enderror"
                               name="otp" value="{{ old('otp') }}" placeholder="Enter OTP"
                               data-parsley-required="true" data-parsley-trigger="change">

                        @error('otp')
                        <span class="invalid-feedback text-start" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-5">
                        <button type="submit" class="btn btn-peaks">
                            Verify
                        </button>
                    </div> --}}
                </form>
                {{-- <div class="form-group pb-3">
                    <span class="t-color">
                        Didn't received OTP?
                        <a class="t-link" href="{{ route('resend.otp') }}"> Resend</a>
                    </span>
                </div>
                <div class="form-group">
                <span class="t-color">
                    Want to change number?
                    <a href="#" class="t-link" data-bs-toggle="modal"
                       data-bs-target="#NumberChangeModal"> Change Number</a>
                </span>
                </div> --}}
            </div>
        </div>
    </section>
    <br>
    @if ($resendCount->count() >= 1)

    @else
        <!-- Number change Modal -->
        <div class="modal fade" id="NumberChangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h6 class="modal-title text-start" id="exampleModalLabel">Change Mobile Number</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('change-mobile-no') }}" method="post" id="form-change-ph-number">
                        @csrf

                        <ul id="ul-error"></ul>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="new-phone" class="text-start mb-2">Enter Mobile number</label>
                                <input id="new-phone" type="tel" name="new_mobile_no" class="input-field input-primary"
                                       value=""
                                       placeholder="Phone number xxx xxx-xxxx">
                                <span id="valid-msg" class="hide  text-start">✓ Valid</span>
                                <span id="error-msg" class="hide text-start"></span>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-sm btn-peaks submitBTN">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


@endsection

@section('js')
    <script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('mask/src/jquery.mask.js') }}"></script>

    <script>
        $(document).on('click', ".new-code", function () {
            $('.loaderElement').show();
        });

        var input = document.querySelector("#new-phone");
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
            // nationalMode: false,
            onlyCountries: ['us'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
        });
    </script>
    <script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>

    <script>
        // $('#otp_form').parsley().on('field:validated', function () {
        //     let ok = $('.parsley-error').length === 0;
        // }).on('field:submit', function () {
        //     console.log('submit form');
        //     return false;
        // });
        $(document).on('click', "#verifyPhoneBtn", function () {
            var form = $('#otp_form');
            form.parsley().validate()
            if (form.parsley().isValid()) {

                var uri = form.attr('action');
                var post_data = form.serialize();
                ajaxPostData(uri, post_data, 'POST', '', 'verifyPhone')

            } else {
                $('#verifyPhoneBtn').attr('disabled', true);
                showToast('warning', 'Please enter valid verification code.');
            }


        });
        $('#form-change-ph-number').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            var phone = $('#new-phone').val();
            if (phone == null || phone == undefined || phone == '') {

                $('#error-msg').html('')
                $('#error-msg').html('Phone number is required.')
                errorMsg.classList.remove("hide");
                return false;
            }

            $.ajax({
                type: "POST",
                url,
                data: form.serialize(),
                beforeSend: function (jqXHR, options) {
                    // setting a timeout
                    $('.loaderElement').show();

                },
                success: (response) => {
                    $("#errors").html('');
                    $('#ul-error').html('');
                    $('.changedPhone').html($('input[name=full_number]').val());
                    $('#NumberChangeModal').modal('hide');
                    $('.NumberChangeModalBTN').remove();
                    $('#NumberChangeModal').remove();
                    showToast('success', 'OTP sent to new phone number.');
                },
                error: (response) => {
                    var loaderEl = $('.loaderElement').hide();
                    $('#ul-error').html('');
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#ul-error').append('<li class="text-danger" style="width: 95%;">' + value + '</li>');
                    });
                },
                complete: function () {
                    var loaderEl = $('.loaderElement').hide();

                }
            });
        });
        $("input[name^=phoneVerifyPin]").attr("autocomplete", "off");

        var keyCode;
        $('input[name^=phoneVerifyPin]').bind('keyup', function (event) {

            var text = $(this).val(),
                key = event.which || event.keyCode || event.charCode;

            if (key == 8) {
                keyCode = key;
                $(this).prev('input[name^=phoneVerifyPin]').focus();

            }
            $('#verifyPhoneBtn').removeAttr('disabled');
        });

        $(document).on("input", "input[name^=phoneVerifyPin]", function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            var text = $(this).val();
            if (text.length == 6) {
                for (i = 1; i <= text.length; i++) {
                    $("input[name^=phoneVerifyPin]").eq(i - 1).val(text[i - 1]);
                }
            } else if (text.length > 1) {

                $(this).val(text[0]);

            }
            if (keyCode == 8) {
                keyCode = null;
            } else {
                $(this).next('input[name^=phoneVerifyPin]').focus();
            }
        });

        @if ($message = Session::get('success'))
            showToast('success', '{{ $message }}');
        @endif

        @if ($message = Session::get('warning'))
            showToast('warning', '{{ $message }}');
        @endif
    </script>
@endsection
