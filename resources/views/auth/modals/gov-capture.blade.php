<div class="modal fade" id="gov-capture-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <ul id="ul-error"></ul>

            <div class="modal-body">
                <h6 class="text-center m-4" id="exampleModalLabel">Capture image</h6>
                <div class="d-flex justify-content-center webcamLoading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <video id="webcam2" autoplay playsinline width="640" height="480"></video>
                <canvas id="canvasGovt" class="d-none"></canvas>
            </div>
            <div class="modal-footer border-0">
                <div class="d-flex flex-row gap-3 justify-content-center align-items-center">
                    <button type="button" id="btnFlipCamera" class="btn btn-peaks-outline btn-small" style="min-width: fit-content !important;">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button type="button" id="govt-cancel-capture" data-bs-dismiss="modal"
                            class="btn btn-peaks-outline btn-small">
                        Cancel
                    </button>
                    <button type="button" id="gov-capture-btn" class="btn btn-peaks btn-small loaderBtn">
                        Capture
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

