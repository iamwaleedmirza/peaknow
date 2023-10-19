@extends('user.dashboard.dashboard')

@section('title') Home @endsection
@section('css')

    <style>

    </style>
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-10">

            <div class="row">
                <div class="col-12">
                    <div class="row mb-5">

                        @if ($pendingOrder)
                            <div class="col-12">
                                <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                    <div class="d-flex justify-content-between">
                                        <i class="fas fa-cart-arrow-down fs-2 mb-3"></i>
                                        <button class="btn btn-peaks-hollow btn-small" data-bs-toggle="modal"
                                                data-bs-target="#payNowModal">
                                            Pay Now
                                        </button>
                                    </div>
                                    <div>
                                        <h6 class="t-semi">Complete Order</h6>
                                        <p>Payment pending for Order No <span
                                                class="t-semi">#PC-{{ $pendingOrder->order_no }}</span></p>
                                        <div class="alert alert-warning fs-6">
                                            <i class="fas fa-info-circle me-2"></i> Your order will be auto-cancelled
                                            after 20 minutes.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- begin: Pay Now Confirmation Modal -->
                            <div class="modal fade" id="payNowModal" tabindex="-1" role="dialog"
                                 aria-labelledby="payNowModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h6 class="modal-title text-start" id="payNowModalLabel">
                                                Confirm Checkout
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body border-0">
                                            <div class="d-flex justify-content-between">
                                                <h6>Order No: </h6>
                                                <h6>#PC-{{ $pendingOrder->order_no }}</h6>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <h6>Total Amount: </h6>
                                                <h5 class="t-bold">${{ $pendingOrder->total_price }}</h5>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-peaks-outline btn-small"
                                                    data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <a href="{{ route('make-unpaid-payment', [$pendingOrder->order_no]) }}">
                                                <button id="proceedUnpaidBTN" type="submit" class="btn btn-peaks btn-small">
                                                    Proceed
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end: pay now Modal -->
                        @endif

                        @if (!empty($activeOrder))
                            @if ($activeOrder->is_subscription)
                                <div class="col-12 col-md-6">
                                    <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                        <div>
                                            <i class="fas fa-credit-card fs-2 mb-3"></i>
                                            <h6 class="t-semi">
                                                @if ($activeOrder->subscription->is_paused == 1)
                                                    Subscription Paused
                                                @elseif ($activeOrder->is_exhausted)
                                                    Plan Expired
                                                @else
                                                    Active Plan
                                                @endif
                                            </h6>
                                            <p class="mb-0">{{ $activeOrder->product_name }}</p>
                                            <p class="t-bold">${{ $activeOrder->total_price }}/month</p>
                                        </div>
                                        <div class="d-flex gap-2 flex-md-column flex-lg-row">
                                            <a href="{{ route('user.plan.index') }}"
                                               class="btn btn-peaks-hollow btn-small mw-fit-content">
                                                Manage
                                            </a>
                                            @if ($activeOrder->is_exhausted && $is_order_exist==1)
                                                <a href="{{ route('plan.renew', [$activeOrder->plan_id,$activeOrder->product_quantity])}}"
                                                   class="btn btn-peaks-hollow btn-small mw-fit-content">
                                                    Renew
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-12 col-md-6">
                                    <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                        <div>
                                            <i class="fas fa-credit-card fs-2 mb-3"></i>
                                            <h6 class="t-semi">
                                                @if ($activeOrder->is_exhausted)
                                                    Plan Expired
                                                @else
                                                    Active Plan
                                                @endif
                                            </h6>
                                            <p class="mb-0">{{ $activeOrder->product_name }}</p>
                                            <p class="t-bold">${{ $activeOrder->total_price }}</p>
                                        </div>
                                        <div class="d-flex gap-2 flex-md-column flex-lg-row">
                                            <a href="{{ route('user.plan.index') }}"
                                               class="btn btn-peaks-hollow btn-small mw-fit-content">
                                                Manage
                                            </a>
                                            @if ($activeOrder->is_exhausted && $is_order_exist==1)
                                                <a href="{{ route('plan.renew', [$activeOrder->plan_id,$activeOrder->product_quantity])}}"
                                                   class="btn btn-peaks-hollow btn-small mw-fit-content">
                                                    Renew
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if (empty($activeOrder) || $activeOrder->is_exhausted)
                            <div class="col-12 col-md-6">
                                <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                    <div>
                                        <i class="fas fa-cart-plus fs-2 mb-3"></i>
                                        <h6 class="t-semi">Create New Order</h6>
                                        <p>Subscribe to a monthly plan or Order one time</p>
                                    </div>
                                    <a data-uri="{{env('WP_URL')}}" class="w-fit-content"
                                       id="checkVerification">
                                        <button class="btn btn-peaks-hollow btn-small">Get Started</button>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if (empty($activeOrder) || !$activeOrder->is_exhausted)
                            <div class="col-12 col-md-6">
                                <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                    <div>
                                        <i class="far fa-question-circle fs-2 mb-3"></i>
                                        <h6 class="t-semi">Have any questions?</h6>
                                        <p>Read about ordering, shipping, payments...</p>
                                    </div>
                                    <a href="{{env('WP_URL')}}/faqs" target="_blank" class="w-fit-content">
                                        <button class="btn btn-peaks-hollow btn-small">Open FAQ</button>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="col-12 col-md-6">
                            <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                <div>
                                    <i class="fas fa-user fs-2 mb-3"></i>
                                    <h6 class="t-semi">My Profile</h6>
                                    <p>View / Edit your Profile</p>
                                </div>
                                <a href="{{ route('account-info') }}" class="w-fit-content">
                                    <button class="btn btn-peaks-hollow btn-small">Manage</button>
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card profile-card d-flex flex-column justify-content-between gap-3">
                                <div>
                                    <i class="fas fa-map-marked-alt fs-2 mb-3"></i>
                                    <h6 class="t-semi">My Addresses</h6>
                                    <p>View your shipping addresses</p>
                                </div>
                                <a href="{{ route('account-addresses') }}" class="w-fit-content">
                                    <button class="btn btn-peaks-hollow btn-small">Manage</button>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).on('click', "#checkVerification", function () {
            let url = $(this).attr('data-uri');
            ajaxPostData('{{route('account-verify-check')}}', '', 'GET', url, 'checkVerification')
        });
        $(document).on('click', "#proceedUnpaidBTN", function () {
            $('.loaderElement').show();
            $('.loaderElement').fadeOut(40000);
        });

        $(document).ready(function () {
            @if(session()->has('success'))
            showToast('success', '{{ session()->get('success') }}');
            @endif
            @if(session()->has('error'))
            showToast('error', '{{ session()->get('error') }}');
            @endif
            @if(session()->has('refillError'))
            showAlert('error', '{{ session()->get('refillError') }}');
            @endif
            @if(session()->has('renewError'))
            showAlertRedirect('error', '{{ session()->get('renewError') }}','{{env('WP_URL')}}/plans');
            @endif

            @if (session()->has('errorMessage'))
            $('.loaderElement').show();
            $('.loaderElement').fadeOut(500);
            setTimeout(() => {
            }, 500);
            @endif
        });
        $(document).on('click', ".resume-btn", function () {
            var uri = $(this).attr('data-uri');
            Swal.fire({
                title: 'Confirmation?',
                text: "You are about to make a refill request for your subscription",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    ajaxPostData(uri, '', 'POST', '', 'refillSub')
                }
            })
        });

    </script>
@endsection
