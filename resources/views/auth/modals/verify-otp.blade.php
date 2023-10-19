<div class="modal fade" id="VerifyPhone" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <ul id="ul-error"></ul>

            <div class="modal-body">
                <h6 class="text-center" id="exampleModalLabel">
                    <img src="{{asset('images/svg/otp2.svg')}}" alt="" style="height: 210px;">
                </h6>
                <h6 class="text-center m-4" id="exampleModalLabel">Verify Your Mobile Number</h6>
                <p class="text-center">Please enter the verification code sent to

                    <strong>
                        <span class="changedPhone h-color">
                       @if (Auth::check())
                                {{Auth::user()->phone}}
                            @endif
                   </span>
                    </strong>
                </p>
                <form class="mb-3" autocomplete="false" id="phonePincodeForm" action="{{route('user.verifyOtpByType')}}"
                      data-parsley-errors-messages-disabled>
                    @csrf
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
                </form>
                <a data-uri="{{ route('resend.otp.ajax') }}" data-type="otp"
                   class="t-link mt-4 ajxbtn loaderBtn">
                    <p class="text-center mb-0">
                        Send a new code
                    </p>
                </a>
            </div>
            <div class="modal-footer border-0">
                <div class="d-flex flex-row gap-3 justify-content-center align-items-center">
                    <button type="button" data-bs-dismiss="modal"
                            class="phoneVerifyCancelBtn btn btn-peaks-outline btn-small">
                        Cancel
                    </button>
                    <button type="button" id="verifyPhoneBtn" class="btn btn-peaks btn-small loaderBtn">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Auth::check())
    <!-- Number change Modal -->
    <div class="modal fade" id="NumberChangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true" data-bs-backdrop="false">
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
                            <label for="new-phone" class="text-start mb-2">Enter mobile number</label>
                            <input id="new-phone" type="tel" name="new_mobile_no"
                                   class="input-field input-primary" value="
                                  {{ Auth::user()->phone }}" required placeholder="Phone number (xxx) xxx-xxxx">

                            <span id="error-msg" class="hide text-start"></span>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-sm btn-peaks changePhoneBtn submitBTN">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endif

