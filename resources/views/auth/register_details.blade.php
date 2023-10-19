@extends('user.base.main')
@section('title') Register @endsection
@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
    <style>
    .iti--allow-dropdown .iti__flag-container,
        .iti--separate-dial-code .iti__flag-container {
            right: auto;
            left: inherit;
        }
        </style>
@endsection
@section('content')
    <section class="section-login text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5">
                <div class="mb-4">
                    <h2 class="h-color">Verify Phone Number</h2>
                </div>
                <form class="mb-4" method="POST" action="{{ route('user.register.second-step') }}"
                      id="registration_form_second">
                    @csrf
                    <div class="row mb-3">
                        <label for="phone" class="t-color text-start text-uppercase mb-2">Phone Number</label>
                        <input id="phone" type="tel"
                               class="input-field input-primary  @error('phone') is-invalid @enderror"
                               value="{{ Auth::check() !== null?Auth::user()->phone:''}}" name="phone" data-parsley-required="true"
                               data-parsley-trigger="change"
                               data-parsley-required-message="Please enter valid phone number!!"
                               placeholder="xxx xxx-xxxx">
                               <span id="error-msg" class="hide text-start"></span>

                    </div>
                    @error('phone')
                    <span class="invalid-feedback text-start" role="alert" style="display:block; position:relative; top: -15px;">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    {{-- <div class="terms-check-box text-start">
                        <div class="form-check checkbox-layout">
                            <input class="form-check-input" type="checkbox" name="msg_agreement" value="1"
                                   id="msg_agreement">
                            <label class="form-check-label t-color" for="msg_agreement">
                                I agree to receiving messages from Peaks
                            </label>
                        </div>
                    </div> --}}

                    <div class="form-group mb-5">
                        <button type="submit" class="btn btn-peaks continue-btn">
                            Next
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('js')
<script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
<script src="{{ asset('mask/src/jquery.mask.js') }}"></script>
<script type="text/javascript">

    $(document).on('click', ".continue-btn", function() {
        // $(this).attr('disabled', 'true')
        var form = $('#registration_form_second');

        form.parsley().validate();

        if (form.parsley().isValid()) {
            $('.loaderElement').show();
            $('#registration_form_second').submit();
        }
 
    });
    setTimeout(() => {
        $('.loaderElement').hide();
    }, 6000);
</script>
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
        $('#registration_form_second').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
        }).on('field:submit', function () {
            console.log('submit form');
            return false;
        });
        @if ($message = Session::get('success'))
            showToast('success', '{{ $message }}')
        @endif
        @if ($message = Session::get('warning'))
            showToast('warning', '{{ $message }}')
        @endif
    </script>
@endsection
