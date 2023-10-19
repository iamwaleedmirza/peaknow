@extends('user.base.main')

@section('title') Order Summary @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-10 col-md-8 col-lg-8 mw-1440px">
                <form action="{{ route('add-to-cart') }}" method="post"
                      enctype="multipart/form-data" id="form-add-to-cart">
                    @csrf

                    <div class="mb-4">
                        <h3 class="h-color">Review your cart</h3>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-uppercase text-center mb-4">Order summary</h6>

                        <div class="row justify-content-around align-items-md-center mb-5">
                            <div class="col-3">
                                <div class="order-img-wrapper">
                                    <img class="order-img" alt="Product Image"
                                         src="{{ $plan->plan_image ? getImage($plan->plan_image) : asset('/images/webp/product-img-1.webp') }}">
                                </div>
                            </div>
                            <div class="col-6 text-start">
                                <div class="order-details">
                                    <h6 class="">{{ $plan->product->name }} ({{$plan->medicine_variant->name}})</h6>
                                    <h6 class="">{{ $plan->plan_type->name }} ({{ $plan->plan_title }})</h6>
                                    <p class="order-description">{{ $plan->plan_details->quantity }} x {{ $plan->strength}}mg</p>
                                </div>
                            </div>
                            <div class="col-3 text-end">
                                <h4 class="order-price">${{ sprintf('%0.2f',$plan->plan_details->price) }}</h4>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-end text-uppercase mb-0">Telemedicine Consultation Fee</p>
                            <h6 class="text-end mb-0 min-w-100px">${{ sprintf('%0.2f',$sdCommission) }}</h6>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-end text-uppercase mb-0">Shipping & Handling Cost</p>
                            <h6 class="text-end mb-0 min-w-100px">@if ($plan->plan_details->shipping_cost<=0)
                                    FREE
                                @else
                                    ${{ sprintf('%0.2f',$plan->plan_details->shipping_cost) }}
                                @endif</h6>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-end text-uppercase mb-0">Subtotal</p>
                            <h6 class="text-end mb-0 min-w-100px">${{ sprintf('%0.2f',$plan->plan_details->price + $sdCommission + $plan->plan_details->shipping_cost) }}</h6>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-end text-uppercase mb-0">Peaks Loyalty Program Member Discount</p>
                            <h6 class="text-end mb-0 min-w-100px">-${{ sprintf('%0.2f',$sdCommission) }}</h6>
                        </div>
                        <hr>

                        @if($plan->plan_details->discount>0)
                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-end text-uppercase mb-0">Subscribe & Save Discount</p>
                            <h6 class="text-end mb-0 min-w-100px">-${{ sprintf('%0.2f',$plan->plan_details->discount) }}</h6>
                        </div>
                        <hr>
                        @endif

                        

                        <div class="d-flex justify-content-end align-items-center mb-5">
                            <p class="mb-0">
                                <span>Have Promo Code?</span>
                                <span id="showPromoInput" class="promo-apply">Apply</span>
                            </p>

                            <div class="d-flex align-items-center gap-3 d-none">
                                <input type="text"
                                       id="inputPromoCode"
                                       name="inputPromoCode"
                                       class="input-field input-primary input-small"
                                       placeholder="Promo code"
                                />
                                <span id="applyPromoCode" class="text-uppercase promo-apply">Apply</span>
                                <span id="cancelPromoCode" class="pointer"><i class="fas fa-times"></i></span>
                            </div>

                            <div id="showAppliedPromoWrapper" class="d-none">
                                <div class="d-flex">
                                    <p class="text-end mb-0">
                                        <span>Applied Promo Code (on&nbsp;Plan&nbsp;Price): </span>
                                        <span id="textPromoCode" class="h-color text-uppercase t-bold"></span>
                                        <span class="text-end t-small d-block">
                                            <span>Discount: </span><span id="textPromoPercent" class="t-semi"></span>
                                        </span>
                                        <span class="text-end t-small d-block">
                                            <span id="changePromoCode" class="promo-apply">Change Promo?</span>
                                            <span id="removePromoCode" class="promo-apply t-color-red">Remove</span>
                                        </span>
                                    </p>
                                    <h6 class="text-end mb-0 min-w-100px">
                                        -<span id="discountAmount" class="h6 text-end mb-0 min-w-100px"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <h4 class="text-end text-uppercase mb-0">TOTAL</h4>
                            <h4 id="totalAmount" class="text-end mx-1 mb-0 min-w-100px"> ${{ sprintf('%0.2f',$plan->plan_details->total) }}</h4>
                        </div>
                    </div>

                    <div class="form-group mb-5 mt-5">
                        <button type="submit" class="btn btn-peaks submitBtn">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('js')

    <script>
        $(document).on('click', ".submitBtn", function () {
            $('.loaderElement').show();
        });

        @if($sdCommission == 0)
        Swal.fire({
            title: '{{ $error['error_title'] }}',
            html: '{{ $error['error_description'] }}',
            icon: 'error',
            showCancelButton: false,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Go Back',
            customClass: {
                icon: 'danger-error-icon',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.replace('{{route("account-home")}}');
            }
        });
            @endif

        const validatePromoCode = function (promoCode, planId,quantity) {
            return new Promise((resolve, reject) => {
                let postUrl = '{{ route('user.validate-promo-code') }}';
                let data = {
                    promo_code: promoCode,
                    plan_id: planId,
                    quantity: quantity,
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: postUrl,
                    cache: false,
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: (response) => {
                        if (response.code === 200) {
                            resolve({
                                message: response.message,
                                promoSummary: response.promoSummary,
                            });
                        } else if (response.code === 404) {
                            reject({message: response.message,});
                        } else {
                            reject({message: 'Something went wrong!',});
                        }
                    },
                    error: (error) => {
                        reject({message: 'Something went wrong!',});
                    },
                    dataType: "json",
                });
            })
        }

        function forceKeyPressUppercase(e)
        {
            let charInput = e.keyCode;
            if((charInput >= 97) && (charInput <= 122)) { // lowercase
                if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
                    let newChar = charInput - 32;
                    let start = e.target.selectionStart;
                    let end = e.target.selectionEnd;
                    e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
                    e.target.setSelectionRange(start+1, start+1);
                    e.preventDefault();
                }
            }
        }

        document.getElementById("inputPromoCode").addEventListener("keypress", forceKeyPressUppercase, false);

        let textPromoCode = $('#textPromoCode');
        let textPromoPercent = $('#textPromoPercent');
        let inputPromoCode = $('#inputPromoCode');
        let showAppliedPromoWrapper = $('#showAppliedPromoWrapper');
        let isPromoApplied = false;

        function setSessionValues() {
            let promoSummary = @json(session()->get('promoSummary'));
            $('#discountAmount').text(`$${promoSummary.discountValue}`);
            $('#totalAmount').text(`$${promoSummary.totalAfterDiscount}`);
            textPromoCode.text(`${promoSummary.promoCode}`);
            textPromoPercent.text(`${promoSummary.discountPercentage}%`);
            $('#showPromoInput').parent().addClass('d-none');
            inputPromoCode.parent().addClass('d-none');
            showAppliedPromoWrapper.removeClass('d-none');
        }

        @if (session('promoSummary'))
            setSessionValues();
        @endif

        $('#showPromoInput').click(function (event) {
            $(this).parent().addClass('d-none');
            inputPromoCode.parent().removeClass('d-none');
        });

        function tryApplyingPromoCode() {
            if (inputPromoCode.val()) {
                $('.loaderElement').show();

                validatePromoCode(inputPromoCode.val(), '{{ $plan->id }}', '{{ $plan->plan_details->quantity }}')
                    .then(response => {
                        $('.loaderElement').hide();
                        showToast('success', response.message);

                        let promoSummary = response.promoSummary;

                        $('#discountAmount').text(`$${promoSummary.discountValue}`);
                        $('#totalAmount').text(`$${promoSummary.totalAfterDiscount}`);
                        textPromoPercent.text(`${promoSummary.discountPercentage}%`);

                        inputPromoCode.parent().addClass('d-none');
                        showAppliedPromoWrapper.removeClass('d-none');
                        textPromoCode.text(inputPromoCode.val());

                        isPromoApplied = true;
                    })
                    .catch(error => {
                        $('.loaderElement').hide();
                        showAlert('error', error.message);
                        inputPromoCode.val('')
                    });
            }
        }

        $('#applyPromoCode').click(function (event) {
            tryApplyingPromoCode();
        });

        inputPromoCode.focusout(function () {
            tryApplyingPromoCode();
        });

        // $('#form-add-to-cart').on('submit', function (event) {
        //     event.preventDefault();
        //     tryApplyingPromoCode();
        // });

        $('#cancelPromoCode').click(function () {
            @if (session('promoSummary'))
                setSessionValues();
            @else
                if (isPromoApplied) {
                    $('#showPromoInput').parent().addClass('d-none');
                    inputPromoCode.parent().addClass('d-none');
                    showAppliedPromoWrapper.removeClass('d-none');
                } else {
                    $('#showPromoInput').parent().removeClass('d-none');
                    inputPromoCode.parent().addClass('d-none');
                }
            @endif
        });

        $('#changePromoCode').click(function (event) {
            showAppliedPromoWrapper.addClass('d-none');
            inputPromoCode.parent().removeClass('d-none');
        });

        const removePromoCode = function (planId,productQty) {
            return new Promise((resolve, reject) => {
                let postUrl = '{{ route('user.remove-promo-code') }}';
                let data = {
                    plan_id: planId,
                    quantity:productQty
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: postUrl,
                    cache: false,
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: (response) => {
                        if (response.code === 200) {
                            resolve({
                                message: response.message,
                                orderSummary: response.orderSummary,
                            });
                        } else if (response.code === 404) {
                            reject({message: response.message,});
                        } else {
                            reject({message: 'Something went wrong!',});
                        }
                    },
                    error: (error) => {
                        reject({message: 'Something went wrong!',});
                    },
                    dataType: "json",
                });
            })
        }

        $('#removePromoCode').click(function () {
            $('.loaderElement').show();

            removePromoCode('{{ $plan->id }}', '{{ $plan->plan_details->quantity }}')
                .then(response => {
                    $('.loaderElement').hide();
                    showToast('success', response.message);

                    let orderSummary = response.orderSummary;

                    $('#discountAmount').text(`$${orderSummary.discountValue}`);
                    $('#totalAmount').text(`$${orderSummary.totalPrice}`);

                    inputPromoCode.parent().addClass('d-none');
                    inputPromoCode.val('');
                    showAppliedPromoWrapper.addClass('d-none');
                    textPromoCode.text('');
                    $('#showPromoInput').parent().removeClass('d-none');

                    isPromoApplied = false;
                })
                .catch(error => {
                    $('.loaderElement').hide();
                    showAlert('error', error.message);
                });
        });

    </script>

@endsection
