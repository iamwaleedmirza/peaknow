<div class="modal fade" id="VerifyEmail" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <ul id="ul-error"></ul>

            <div class="modal-body">
                <h6 class="text-center" id="exampleModalLabel">
                    <img src="{{asset('images/svg/otp1.svg')}}" alt="" style="height: 210px;">
                </h6>
                <h6 class="text-center m-4" id="exampleModalLabel">Verify Your Email Address</h6>
                <p class="text-center">An Email Verification is sent to your account
                    <span class="changedEmail h-color">
                        @if (Auth::check())
                            {{Auth::user()->email}}
                        @endif
                    </span>

                </p>
                <form class="mb-3" id="emailPincodeForm" action="{{route('user.verifyOtpByType')}}"
                      data-parsley-errors-messages-disabled>
                    @csrf
                    <input type="text" hidden name="type" value="email">
                    <div class="d-flex form-group justify-content-center text-center px-5">
                        <input autocomplete="false" type="number" name="emailVerifyPin[1]"
                               class="pincode-input email-code col otp-field" required>
                        <input autocomplete="false" type="number" name="emailVerifyPin[2]"
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
                <a data-uri="{{ route('email-resend-verify-ajax') }}" data-token="{{ csrf_token() }}"
                   class="t-link mt-4 ajxbtn loaderBtn">
                    <p class="text-center mb-0">
                        Send a new code
                    </p>
                </a>
            </div>
            <div class="modal-footer border-0">
                <div class="d-flex flex-row gap-3 justify-content-center align-items-center">
                    <button type="button" data-bs-dismiss="modal"
                            class="emailVerifyCancelBtn btn btn-peaks-outline btn-small">Cancel
                    </button>
                    <button type="button" id="verifyEmailBtn" class="btn btn-peaks btn-small loaderBtn">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Auth::check())
    <div class="modal fade" id="EmailChangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-start" id="exampleModalLabel">Change Email</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('change-email') }}" method="post" id="form-change-email">
                    @csrf

                    <ul id="ul-error"></ul>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new-phone" class="text-start mb-2">Enter Email</label>
                            <input id="new-phone" type="email" name="new_email"
                                   class="input-field input-primary" value="{{ Auth::user()->email }}" required>
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
