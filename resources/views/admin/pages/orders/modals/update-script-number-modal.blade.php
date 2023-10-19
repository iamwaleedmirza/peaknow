<div class="modal fade" id="liberty_script_number_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content refund-declined-order-form">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle"
                    style="text-align: center;font-weight: 600;margin-top: -6px;">Update Liberty Script Details</h3>
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
            <form id="liberty_script_number_form" class="modal_body" method="post">
            @csrf
                <input type="hidden" name="order_no" id="order_no" value="{{$order->id}}">
                <div class="modal-body">
                    <div class="form-group mb-3" id="track_number_div">
                        <span class="form-label" for="">Liberty Script Number</span>
                        <input type="text" class="form-control mt-1" placeholder="Enter Liberty Script Number" name="liberty_script_number" id="liberty_script_number" value="{{$order->script_number}}" maxlength="15" onkeypress="return IsNumeric(event)">
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