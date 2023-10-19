<div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content refund-cancelled-order-form">
        <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLongTitle">Edit Medicine Variant Details</h3>
            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                aria-label="Close">
                <!--begin::Svg Icon | path: assets/media/icons/duotune/abstract/abs012.svg-->
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none">
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
        <div id="modal_loader" class="modal_loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>
        <form id="data_form" class="modal_body" method="post">
            @csrf
            <input type="hidden" name="id" id="id" value="" readonly>
            <div class="modal-body" id="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="form-group">
                            <span class="form-label fw-bold fs-6 mb-2">Medicine Variant <span class="error-sign">*</span></span>
                            <input type="text" name="name" placeholder="Enter Medicine Variant" class="form-control form-control-solid mb-2 mt-2" id="name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group text-right m-b-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="ladda-button btn btn-primary " data-style="slide-right" type="submit" id="Modal-btn">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>