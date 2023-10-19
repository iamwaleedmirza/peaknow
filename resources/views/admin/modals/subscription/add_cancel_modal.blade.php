<div class="modal fade" tabindex="-1" id="cancel_reason_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancellation Reason</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                aria-label="Close">
                <!--begin::Svg Icon | path: assets/media/icons/duotune/abstract/abs012.svg-->
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3"
                            d="M6.7 19.4L5.3 18C4.9 17.6 4.9 17 5.3 16.6L16.6 5.3C17 4.9 17.6 4.9 18 5.3L19.4 6.7C19.8 7.1 19.8 7.7 19.4 8.1L8.1 19.4C7.8 19.8 7.1 19.8 6.7 19.4Z"
                            fill="black" />
                        <path
                            d="M19.5 18L18.1 19.4C17.7 19.8 17.1 19.8 16.7 19.4L5.40001 8.1C5.00001 7.7 5.00001 7.1 5.40001 6.7L6.80001 5.3C7.20001 4.9 7.80001 4.9 8.20001 5.3L19.5 16.6C19.9 16.9 19.9 17.6 19.5 18Z"
                            fill="black" />
                    </svg></span>
                <!--end::Svg Icon-->

            </div>
                <!--end::Close-->
            </div>
            <form method="post" id="cancel-form">
                @csrf
                <input type="hidden" name="order_id" id="cancel_order_id" value="">
                <div class="modal-body">
                    <div id="other-reason-wrapper" class="form-group mt-3">
                        <label for="textarea-other-reason" class="text-start mb-2">Cancellation reason</label>
                        <textarea id="cancel_reason" name="cancel_reason" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group text-right m-b-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary " data-style="slide-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>