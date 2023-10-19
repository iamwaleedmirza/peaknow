<div class="card card-flush  flex-row-fluid">
    <!--begin::Card header-->

    <div class="card-header">
        <div class="card-title">
            <h2>Refill Details (#PC-{{ $order->order_no }})</h2>
        </div>
    </div>
    @php
        $orderConfirmed = false;
        $shippingConfirmed = false;
        
        if ($orderRefill->refill_status == 'Confirmed' || $orderRefill->refill_status == 'Completed') {
            $orderConfirmed = true;
        }
        if ($orderRefill->is_shipped) {
            $shippingConfirmed = true;
        }
    @endphp
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                <!--begin::Table body-->
                <tbody class="">
                    @if ($orderRefill->refill_number == 0)
                        <tr>
                            <td class="text-muted">
                                <div class="d-flex align-items-center">
                                    <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                    <span class="svg-icon svg-icon-2 me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
                                                fill="currentColor" />
                                            <path
                                                d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->Order Type
                                </div>
                            </td>
                            <td class="fw-bolder text-end">
                                {{ 'New Fill' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-muted">
                                <div class="d-flex align-items-center">
                                    <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                    <span class="svg-icon svg-icon-2 me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
                                                fill="currentColor" />
                                            <path
                                                d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->Refill Number
                                </div>
                            </td>
                            <td class="fw-bolder text-end">
                                {{ $orderRefill->refill_number ?: '-' }}
                            </td>
                        </tr>
                    @endif
                    <!--begin::Date-->
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                <span class="svg-icon svg-icon-2 me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21"
                                        viewBox="0 0 20 21" fill="none">
                                        <path opacity="0.3"
                                            d="M19 3.40002C18.4 3.40002 18 3.80002 18 4.40002V8.40002H14V4.40002C14 3.80002 13.6 3.40002 13 3.40002C12.4 3.40002 12 3.80002 12 4.40002V8.40002H8V4.40002C8 3.80002 7.6 3.40002 7 3.40002C6.4 3.40002 6 3.80002 6 4.40002V8.40002H2V4.40002C2 3.80002 1.6 3.40002 1 3.40002C0.4 3.40002 0 3.80002 0 4.40002V19.4C0 20 0.4 20.4 1 20.4H19C19.6 20.4 20 20 20 19.4V4.40002C20 3.80002 19.6 3.40002 19 3.40002ZM18 10.4V13.4H14V10.4H18ZM12 10.4V13.4H8V10.4H12ZM12 15.4V18.4H8V15.4H12ZM6 10.4V13.4H2V10.4H6ZM2 15.4H6V18.4H2V15.4ZM14 18.4V15.4H18V18.4H14Z"
                                            fill="black" />
                                        <path
                                            d="M19 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V4.40002C0 5.00002 0.4 5.40002 1 5.40002H19C19.6 5.40002 20 5.00002 20 4.40002V1.40002C20 0.800024 19.6 0.400024 19 0.400024Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->Refill Requested On
                            </div>
                        </td>
                        <td class="fw-bolder text-end">
                            {{ $orderRefill->refill_date? \Carbon\Carbon::parse($orderRefill->refill_date)->format('M d, Y'): \Carbon\Carbon::parse($orderRefill->created_at)->format('M d, Y') }}
                        </td>
                    </tr>
                    <!--end::Date-->

                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
    </div>
    <!--end::Card body-->

</div>
<div class="card card-flush  flex-row-fluid">
    <div class="card-header">
        <div class="card-title">
            <h2> Tracking Number</h2>
        </div>
    </div>
    <div class="card-body">
        <form id="form-order-refill-tracking" method="POST" action="{{route('admin.order.refill.tracking.data.post')}}">
            @csrf
            <input type="text" hidden name="order_no" value="{{$orderRefill->order_no}}">
            <input type="text" hidden name="refill_no" value="{{$orderRefill->refill_number}}">
            <div class="mb-3 fv-row">
            <input type="text" name="tracking_number" class="form-control form-control-solid" placeholder="Enter Tracking number" value="{{$orderShipment?$orderShipment->tracking_number:''}}"/>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary" id="refill-tracking-btn">Submit</button>
            </div>
            
        </form>
    </div>
</div>
<script>

var validator = FormValidation.formValidation(
    document.getElementById('form-order-refill-tracking'),
    {
        fields: {
            'tracking_number': {
                validators: {
                    notEmpty: {
                        message: 'Tracking number is required'
                    }
                }
            },
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);
setTimeout(() => {
      // Submit button handler
      var submitButton = document.getElementById('refill-tracking-btn');
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;
                            var form = $("#form-order-refill-tracking")
                            var post_data = form.serialize();
                            var uri = form.attr('action');
                            var reloaduri = $('#orderDataURI').val();

                            ajaxPostData(uri, post_data, 'POST', '', 'updateRefillTracking', '' , true)
                                                
                    }
                });
            }
        });
}, 0);
</script>