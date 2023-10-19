<div class="modal fade" id="unship-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content refund-declined-order-form">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle"
                    style="text-align: center;font-weight: 600;margin-top: -6px;">Update Refill Shipment Details</h3>
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
            <form id="data_form" class="modal_body" method="post">
            @csrf
                <input type="hidden" name="order_detail_id" id="order_detail_id" value="0">
                <div class="modal-body">
                        <div data-kt-buttons="true">
                            <!--begin::Radio button-->
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start p-6 mb-5 active">
                                <!--end::Description-->
                                <div class="d-flex align-items-center me-2">
                                    <!--begin::Radio-->
                                    <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                        <input class="form-check-input unship-type" id="ship-with-tracking-no" type="radio" name="unship_type" value="1" checked />
                                    </div>
                                    <!--end::Radio-->

                                    <!--begin::Info-->
                                    <div class="flex-grow-1">
                                        <h2 class="d-flex align-items-center fs-3 fw-bold flex-wrap">
                                            Ship with tracking number
                                        </h2>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Price-->
                            </label>
                            <!--end::Radio button-->

                            <!--begin::Radio button-->
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-stack text-start p-6 mb-5">
                                <!--end::Description-->
                                <div class="d-flex align-items-center me-2">
                                    <!--begin::Radio-->
                                    <div class="form-check form-check-custom form-check-solid form-check-primary me-6">
                                        <input class="form-check-input unship-type" type="radio" name="unship_type" value="2"/>
                                    </div>
                                    <!--end::Radio-->

                                    <!--begin::Info-->
                                    <div class="flex-grow-1">
                                        <h2 class="d-flex align-items-center fs-3 fw-bold flex-wrap">
                                            Ship without tracking number
                                        </h2>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="form-group mb-3" id="mark_track_number_div">
                            <label class="form-label" for="">Tracking Number</label>
                            <input type="text" class="form-control" placeholder="Enter Tracking Number" name="tracking_no" id="tracking_no" value="" maxlength="15" onkeypress="return IsNumeric(event)">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Select Shipment Date</label>
                            <input type="text" class="form-control" placeholder="Select Date" name="ship_date" id="ship_date" value="">
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