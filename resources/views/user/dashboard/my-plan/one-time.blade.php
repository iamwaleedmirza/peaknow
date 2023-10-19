@extends('user.dashboard.dashboard')

@section('title')
    My Plan
@endsection
@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/peaks/order-tracker.css') }}" rel="stylesheet">
    <style>
        .btn-small {
            height: auto;
        }
    </style>
@endsection
@section('content')

    <div class="d-flex flex-column gap-4 mb-5">
        <div class="d-flex flex-wrap">
            <h4>Active Plan</h4>
        </div>

        <div class="d-flex flex-column flex-xl-row gap-4 gap-lg-4">

            <!--begin::Order details-->
            <div class="card card-flush py-4 flex-row-fluid flex-fill">

                <div class="card-header">
                    <div class="card-title">
                        <h6>Order Details</h6>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody>
                            <tr>
                                <td><div class="d-flex align-items-center">Order No</div></td>
                                <td class="fw-bolder text-end">#{{ 'PC-'.$order->order_no }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">Ordered On</div>
                                </td>
                                <td class="fw-bolder text-end">
                                    {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">Plan Status</div>
                                </td>
                                <td class="fw-bolder text-end">
                                    @if ($order->is_exhausted == 0)
                                        <span class="status status__success">Active</span>
                                    @else
                                        <span class="status status__danger">Expired</span>
                                    @endif
                                </td>
                            </tr>
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <div class="d-flex align-items-center">Refills used</div>--}}
{{--                                </td>--}}
{{--                                <td class="fw-bolder text-end">{{ $order->refill_count + 1 }} of 6</td>--}}
{{--                            </tr>--}}
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">Payment Method</div>
                                </td>
                                <td class="fw-bolder text-end">{{ $order->payment_method?:'-' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Order details-->

            <!--begin::Shipping address-->
            <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        <h6>Shipping Details</h6>
                    </div>
                </div>
                <div class="card-body pt-1" style="max-width: 250px;">
                    <div class="mb-3">
                        <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                            {{ $order->shipping_fullname}}
                        </p>
                        <div class="d-flex">
                            <div>
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="ms-2">
                                <p class="mb-0">{{ $order->shipping_address_line }}</p>
                                <p class="mb-0">{{$order->shipping_address_line2 }}</p>
                                <p class="mb-1">
                                    {{ $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zipcode }}</p>
                                {{-- <p class="mb-1 ">
                                    {{ $order->shipping_address_line }} {{ $order->shipping_address_line2?','.$order->shipping_address_line2.',':'' }}  {{ $order->shipping_city.', '.$order->shipping_state.' - '.$order->shipping_zipcode }}
                                </p> --}}
                            </div>
                        </div>
                        <p class="mb-0"><i class="fas fa-phone-alt"></i> {{ $order->shipping_phone?:'-' }}</p>
                    </div>
                    @if ($order->status == 'Prescribed' && $order->is_exhausted == 0 && Auth::user()->liberty_patient_id)
                        <button class="btn btn-peaks-hollow btn-small edit-address"
                                data-id="{{ $order->id }}"
                                data-address="{{ $order->shipping_address_line }}"
                                data-address2="{{ $order->shipping_address_line2 }}"
                                data-city="{{ $order->shipping_city }}"
                                data-zipcode="{{ $order->shipping_zipcode }}"
                                data-state="{{ $order->shipping_state }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editAddressModal">
                            Change
                        </button>
                    @endif
{{--                    @if ($order->status == 'Pending' && $order->is_exhausted == 0 && $order->transaction()->count() !== 0)--}}
{{--                        <button class="btn btn-peaks-hollow btn-small edit-address"--}}
{{--                                data-id="{{ $order->id }}"--}}
{{--                                data-address="{{ $order->shipping_address_line }}"--}}
{{--                                data-address2="{{ $order->shipping_address_line2 }}"--}}
{{--                                data-city="{{ $order->shipping_city }}"--}}
{{--                                data-zipcode="{{ $order->shipping_zipcode }}"--}}
{{--                                data-state="{{ $order->shipping_state }}"--}}
{{--                                data-bs-toggle="modal"--}}
{{--                                data-bs-target="#editAddressModal">--}}
{{--                            Change--}}
{{--                        </button>--}}
{{--                    @endif--}}
                </div>
            </div>
            <!--end::Shipping address-->

        </div>
        <!--end::Order summary-->

        @include('user.dashboard.my-plan.includes.__cancellation-request-card')

        @if ($order->transaction()->count() !== 0)
        <!--begin::Order tracking-->
        @include('user.dashboard.my-plan.includes._refill-tracking')
        <!--end::Order tracking-->
        @endif

        <!--begin::Orders-->
        <div class="d-flex flex-column gap-4 gap-lg-4">
            <div class="d-flex flex-column flex-xl-row gap-2 gap-lg-4">

                <!--begin::Plan details-->
                <div class="card card-flush py-4 flex-row-fluid flex-fill">

                    <div class="card-header">
                        <div class="card-title">
                            <h6>Plan Details</h6>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">Plan Name</div>
                                    </td>
                                    <td class="fw-bolder text-end">{{ $order->plan_name }} ({{ $order->plan_title}})</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">Product Description</div>
                                    </td>
                                    <td class="fw-bolder text-end">{{ $order->product_name }} ({{$order['product_quantity']}} X {{$order['strength']}}mg)</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">Plan Price</div>
                                    </td>
                                    <td class="fw-bolder text-end">${{ $order->total_price }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Plan details-->

            </div>

            <!--begin::Refill History-->
            @include('user.dashboard.my-plan.includes._refill-history')
            <!--end::Refill History-->

            <div class="flex-row-fluid overflow-hidden">
                <div class="card-body py-0">
                    <div
                        class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">

                        @if ($order->status == 'Pending')
                            @if($order->payment_status == 'Paid' && !$order->cancellation_request)
                                <button class="btn btn-peaks-danger"
                                        onclick="cancelOrder('{{$order->id}}')"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#cancel_modal">
                                    Cancel Order
                                </button>
                            @endif
                        @elseif($order->status == 'Prescribed')
                            <a href="{{ route('change.payment_method') }}">
                                <button class="btn btn-peaks-outline">
                                    Update Payment Method
                                </button>
                            </a>
                            @if ($order->is_exhausted === 0)
                                @if ($order->orderRefill()->orderBy('created_at', 'DESC')->first()->is_shipped == 1)
                                    <a href="{{ route('order.request-refill') }}" id="btnRequestForRefill">
                                        <button type="button" class="btn btn-peaks-outline">
                                            Request for Refill
                                        </button>
                                    </a>
                                @endif

                            @else
                                @if($is_order_exist==1)
                                <a href="{{ route('plan.renew', [$order->plan_id,$order->product_quantity])}}">
                                    <button type="button" class="btn btn-peaks-outline">
                                        Renew Plan
                                    </button>
                                </a>
                                @endif
                            @endif
                            @if ($order->is_changed == false)
                                <button class="btn btn-peaks-outline change-plan-btn"
                                data-uri="{{route('user.change.plan')}}">
                                    Change your plan
                                </button>
                            @endif

                        @endif

                    </div>
                </div>
            </div>

        </div>
        <!--end::Orders-->
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-start" id="confirmationModalLabel">WAIT...</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Don't have to cancel just yet, you can pause your plan for now get a refill you may need it
                        sooner or later!</p>
                </div>
                <div class="modal-footer border-0 text-end">
                    <button type="button" class="btn btn-peaks-danger" data-bs-toggle="modal"
                            data-bs-target="#unsubscribe_modal" onclick="$('#confirmation_modal').modal('hide')">Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- cancellation Modal -->
    <div class="modal fade" id="cancel_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-start" id="exampleModalLabel">Cancellation Reason</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cancel.order') }}" method="post" id="cancel-form">
                    @csrf
                    <input type="hidden" name="order_id" id="cancel_order_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="cancel-reason" class="text-start mb-2">Please select the reason for
                                cancellation</label>
                            <select required id="cancel-reason" name="cancel_reason" class="input-field input-primary">
                                <option value="" selected disabled hidden>Select Reason</option>
                                <option value="Service issues">Service issues</option>
                                <option value="Just wanted to try">Just wanted to try</option>
                                <option value="Trying other service">Trying other service</option>
                                <option value="Not worth the price">Not worth the price</option>
                                <option value="Accidentally ordered more than once">Accidentally ordered more than once</option>
                                <option value="Purchased as a gift">Purchased as a gift</option>
                                <option value="Did not like the product">Did not like the product</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div id="other-reason-wrapper" class="form-group mt-3" style="display: none;">
                            <label for="textarea-other-reason" class="text-start mb-2">Other reason</label>
                            <textarea id="textarea-other-reason"
                                      name="cancel_reason_other"
                                      class="input-field input-primary"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 text-end">
                        <button type="button" class="btn btn-peaks-outline" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-peaks">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('user.dashboard.includes._change-shipping-address-modal')
    @include('user.dashboard.includes._select-shipping-address-modal')

@endsection
@section('js')
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/js/order-address.js') }}"></script>
    <script src="{{ asset('/js/additional-methods.min.js') }}"></script>

    <script type="text/javascript">
        $('#cancel-form').validate({ // initialize the plugin
            rules: {
                cancel_reason: {
                    required: true
                }
            },
            messages: {
                cancel_reason: {
                    required: "Please select the reason for cancellation"
                }
            },
            submitHandler: function (form) {
                form.submit();
                $('.loaderElement').show();
                var loaderbtn = $('.loaderBtn');
                loaderbtn.attr('disabled', true);
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#user-order-table').DataTable();

            @if (session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: '<span style="font-size: 22px;">Your order has been cancelled.<span>',
                text: '{{ session()->get('success') }}',
                animation: true,
                showConfirmButton: true,
            }).then((response) => {
                if (response.isConfirmed) {
                    window.location.replace('{{ route('account-home') }}');
                }
            });
            @endif
            @if (session()->has('error'))
            showToast('error', '{{ session()->get('error') }}');
                @endif

            const otherReasonWrapper = $('#other-reason-wrapper');
            const textAreaOtherReason = $('#textarea-other-reason');

            $('#cancel-reason').on('click', function () {
                if (this.value === 'others') {
                    otherReasonWrapper.show();
                    textAreaOtherReason.attr('required', '');
                } else {
                    otherReasonWrapper.hide();
                    textAreaOtherReason.removeAttr('required', '');
                }
            });

        });

        function cancelOrder(order_id) {
            $("#cancel_order_id").val(order_id);
        }

    </script>
    <script type="text/javascript">

        $(document).on('click', ".change-plan-btn", function () {
            var uri = $(this).attr('data-uri');
            Swal.fire({
                title: 'Confirmation?',
                text: "Are you sure you want to change plan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace(uri);
                }
            })

        });
        $(document).on('click', ".pause-btn", function () {
            var uri = $(this).attr('data-uri');
            Swal.fire({
                title: 'Confirmation?',
                text: "Are you sure you want to pause subscription!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    ajaxPostData(uri, '', 'POST', '', 'pauseSub')
                }
            })

        });
        $(document).on('click', ".resume-btn", function () {
            var uri = $(this).attr('data-uri');
            Swal.fire({
                title: 'Confirmation?',
                text: "Are you sure you want to resume subscription!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    ajaxPostData(uri, '', 'POST', '', 'resumeSub')
                }
            })
        });

        $('#btnRequestForRefill').click(function () {
            $('.loaderElement').show();
            @if (auth()->user()->payment_profile_id)
                $('#loaderElementText').text('Processing Payment...');
            @endif
        })

    </script>

@endsection
