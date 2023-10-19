@extends('user.base.main')

@section('title')
    Email Verification
@endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
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
                    <h4 class="h-color">Email Verification</h4>
                </div>
                <div class="modal-body">
                    <h6 class="text-center" id="exampleModalLabel">
                        <img src="{{ asset('images/svg/otp1.svg') }}" alt="" style="height: 210px;">
                    </h6>
                    <h6 class="text-center my-4" id="exampleModalLabel">Verify Your Email Address</h6>
                    <p class="text-center">Please enter verification code sent to your email 
                        <strong>
                            <span class="changedEmail h-color">
                                @if (Auth::check())
                                    {{ Auth::user()->email }}
                                @endif
                            </span>
                        </strong>
                    </p>
                    <form autocomplete="false" id="emailPincodeForm" action="{{ route('user.verifyOtpByType') }}"
                        data-parsley-errors-messages-disabled>
                        @csrf
                        <input type="text" hidden name="type" value="email">
                        <div class="d-flex form-group justify-content-center text-center px-5">
                            <input autocomplete="false" type="number" name="emailVerifyPin[1]"
                                class="pincode-input email-code col otp-field" required>
                            <input autocomplete="false" type="number otp-field" name="emailVerifyPin[2]"
                                class="pincode-input email-code col otp-field" required>
                            <input autocomplete="false" type="number" name="emailVerifyPin[3]"
                                class="pincode-input email-code col otp-field" required>
                            <input autocomplete="false" type="number" name="emailVerifyPin[4]"
                                class="pincode-input email-code col otp-field" required>
                            <input autocomplete="false" type="number" name="emailVerifyPin[5]"
                                class="pincode-input email-code col otp-field" required>
                            <input autocomplete="false" type="number" name="emailVerifyPin[6]"
                                class="pincode-input email-code col otp-field" required>
                        </div>
                    </form>
                    @php
                        $resendCount = \App\Models\UserLogs::where('user_id', Auth::user()->id)
                            ->where('type', 'changeEmail')
                            ->orderBy('created_at', 'DESC')
                            ->get();
                    @endphp
                    @if ($resendCount->count() >= 1)
                    @else
                        <p class="my-2 text-center EmailChangeModalBTN">
                            <a href="#EmailChangeModal" class="t-link" data-bs-toggle="modal"
                                data-bs-target="#EmailChangeModal">
                                Do you want to change email address?
                            </a>

                        </p>
                    @endif

                </div>
                @php
                    $userLogs = Auth::user()->getResentVerificationByType('resentEmail');
                @endphp
                @if ($userLogs !== null)
                    @php
                        $logDate = strtotime(date('Y-m-d H:i:s', strtotime($userLogs->created_at))) + 60 * 1;
                    @endphp
                    {{-- <div class="alert alert-warning alert-block mt-3">
                        <span>Try again after {{$logDate}}</span>
                    </div> --}}
                @else
                @endif
                <div class="modal-footer justify-content-center border-0">
                    <div class="d-flex flex-column align-items-center flex-md-row gap-2">
                        <a href="{{ route('email-resend-verify') }}">
                            <button type="button" class="btn btn-peaks new-code">Send a new code</button>
                        </a>
                        <button type="button" id="verifyEmailBtn" class="btn btn-peaks loaderBtn">Next</button>
                    </div>
                </div>
                {{-- <form class="mb-4" method="get" action="{{route('rx-user-agreement')}}" id="otp_form">
                    @csrf

                    <div class="row mb-3">
                        <label for="age" class="t-color text-uppercase mb-2">Please check your email to verify your account</label>


                        @error('otp')
                        <span class="invalid-feedback text-start" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-5">
                        @if (Auth::user()->email_verified == 1)
                        <button type="submit" class="btn btn-peaks">
                            Proceed
                        </button>
                        @else
                        <button type="button" class="btn btn-peaks">
                            Waiting for verification
                        </button>
                        @endif

                    </div>
                </form> --}}
                @if (Auth::user()->email_verified == 0)
                    <div class="form-group pb-3">

                        {{-- <span class="t-color">
                        Didn't received Email?

                        <form method="GET" action="{{ route('email-resend-verify') }}">

                            <button type="submit" style="background: none;
                            color: inherit;
                            border: none;
                            padding: 0;
                            font: inherit;
                            cursor: pointer;
                            outline: inherit;" class="t-link">Resend</button>
                        </form>
                    </span> --}}
                    </div>
                    {{-- <div class="form-group">
                    <span class="t-color">
                        Want to change email?
                        <a href="#" class="t-link" data-bs-toggle="modal"
                           data-bs-target="#NumberChangeModal"> Change Email</a>
                    </span>
                    </div> --}}
                @endif

            </div>
        </div>
    </section>
    <br>
    @if ($resendCount->count() >= 1)
    @else
        <div class="modal fade" id="EmailChangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h6 class="modal-title text-start" id="exampleModalLabel">Change Email</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('change-email') }}" method="post" id="form-change-ph-number">
                        @csrf

                        <ul id="ul-error"></ul>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email" class="text-start mb-2">Enter Email</label>
                                <input id="email" type="email" name="new_email" class="input-field input-primary"
                                    value="" placeholder="Enter new email address">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-sm btn-peaks">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <!-- email change Modal -->
@endsection

@section('js')
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).on('click', ".new-code", function() {

            $('.loaderElement').show();


        });
        $(document).ready(function() {
            $('#form-change-ph-number').validate({ // initialize the plugin
                rules: {
                    new_email: {
                        required: true,
                        email: true
                    },

                },
                messages: {
                    new_email: {
                        required: "Email is required."
                    },

                },
                submitHandler: function(form) {

                        var form = $('#form-change-ph-number');
                        let url = form.attr('action');
                        var email = $('#email').val();
                        if (email == null || email == undefined || email == '') {

                            return false;
                        }
                        $.ajax({
                            type: "POST",
                            url,
                            data: form.serialize(),
                            beforeSend: function(jqXHR, options) {
                                // setting a timeout
                                $('.loaderElement').show();

                            },
                            success: (response) => {
                                $("#errors").html('');
                                $('#ul-error').html('');
                                $('.changedEmail').html($('#email').val());
                                $('#EmailChangeModal').modal('hide');
                                $('.EmailChangeModalBTN').remove();
                                $('#EmailChangeModal').remove();

                                showToast('success', 'Email verification code has been resent successfully to your updated email.');
                            },
                            error: (response) => {
                                var loaderEl = $('.loaderElement').hide();
                                $('#ul-error').html('');
                                // if (response.responseJSON.message != null) {
                                //     $('#ul-error').append(
                                //         '<li class="text-danger">Something happeneds or Email Exists</li>'
                                //     );
                                // }
                                $.each(response.responseJSON.errors, function(key,
                                    value) {
                                    $('#ul-error').append(
                                        '<li class="text-danger" style="width: 95%;">' +
                                        value + '</li>');
                                });
                            },
                            complete: function() {
                                var loaderEl = $('.loaderElement').hide();

                            }
                        });

                }
            });

        });
        $("input[name^=emailVerifyPin]").attr("autocomplete", "off");
        var keyCode;
        $('input[name^=emailVerifyPin]').bind('keyup', function(event) {

            var text = $(this).val(),
                key = event.which || event.keyCode || event.charCode;
                $('#verifyEmailBtn').removeAttr('disabled');
            if (key == 8) {
                keyCode = key;
                $(this).prev('input[name^=emailVerifyPin]').focus();
                return false;
            }

        });
        $(document).on("input", "input[name^=emailVerifyPin]", function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            var text = $(this).val();
            if (text.length == 6) {
                for (i = 1; i <= text.length; i++) {
                    $("input[name^=emailVerifyPin]").eq(i - 1).val(text[i - 1]);
                }
            } else if (text.length > 1) {
                $(this).val(text[0]);

            }
            if (keyCode == 8) {
                keyCode = null;
            } else {
                $(this).next('input[name^=emailVerifyPin]').focus();
            }



        });
        $(document).on('click', "#verifyEmailBtn", function() {
            var form = $('#emailPincodeForm');
            form.parsley().validate()
            if (form.parsley().isValid()) {

                var uri = form.attr('action');
                var post_data = form.serialize();
                ajaxPostData(uri, post_data, 'POST', '', 'verifyEmail')

            } else {
                $('#verifyEmailBtn').attr('disabled',true);
                showToast('warning', 'Please enter valid verification code.');
            }
        });

        @if ($message = Session::get('success'))
            showToast('success', '{{ $message }}')
        @endif

        @if ($message = Session::get('warning'))
            showToast('warning', '{{ $message }}')
        @endif
    </script>
@endsection
