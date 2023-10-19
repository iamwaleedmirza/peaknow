@extends('user.dashboard.dashboard')

@section('title') Order Detail @endsection

@section('css')
    <link href="{{ asset('/css/peaks/order-tracker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
@endsection

@section('content')
    <div class="d-flex flex-column gap-4 mb-5">
        <div class="d-flex flex-wrap">
            <h4>Order Summary</h4>
            <a href="{{ route('account-orders') }}" class="ms-auto me-lg-n7">
                <button class="btn btn-peaks btn-small">Back</button>
            </a>
        </div>

        @include('user.dashboard.my-plan.includes.__cancellation-request-card')

        <div class="d-flex flex-column flex-xl-row gap-4 gap-lg-4">

            <!--begin::Order details-->
            <div class="card card-flush py-4 flex-row-fluid flex-fill">

                <div class="card-header">
                    <div class="card-title">
                        <h6>Order Details (#{{ 'PC-'.$order->order_no }})</h6>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        Ordered On
                                    </div>
                                </td>
                                <td class="fw-bolder text-end">
                                    {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        Payment Status
                                    </div>
                                </td>
                                <td class="fw-bolder text-end">
                                    @if ($order->payment_status == 'Paid')
                                        <span class="status status__success">{{ $order->payment_status }}</span>
                                    @else
                                        <span class="status status__warning">{{ 'Pending' }}</span>
                                    @endif
                                </td>
                            </tr>

                            @if ($order->payment_method)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        Payment Method
                                    </div>
                                </td>
                                <td class="fw-bolder text-end">{{ $order->payment_method }}
                            </tr>
                            @endif
                            @if ($order->cancellation_request==0)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        Order Status
                                    </div>
                                </td>
                                <td class="fw-bolder text-end">
                                    <div>
                                        @if ($order->status == 'Cancelled')
                                            <span class="status status__danger">Cancelled</span>
                                        @elseif ($order->status == 'Cancelled')
                                            <span class="status status__danger">{{ $order->status }}</span>
                                        @elseif($order->status == 'Prescribed')
                                            <span class="status status__primary"> {{ $order->status }}</span>
                                        @elseif($order->status == 'Declined')
                                            <span class="status status__danger"> {{ $order->status }}</span>
                                        @elseif($order->status == 'Pending')
                                            <span class="status status__info"> {{ 'Confirmed' }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @if ($order->status == 'Cancelled')
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            Cancellation Reason
                                        </div>
                                    </td>
                                    <td class="fw-bolder text-end " style="max-width: 190px;">
                                        <div>
                                            <span>{{$order->cancel_reason}}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if ($order->status == 'Declined')
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            Declined Reason
                                        </div>
                                    </td>
                                    <td class="fw-bolder text-end " style="max-width: 190px;">
                                        <div>

                                            <span class="status status__danger">{{($order->doctor_response!='') ? $order->doctor_response : 'Declined By Doctor'}}</span>

                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Order details-->

            <!--begin::Customer details-->
        {{--            <div class="card card-flush py-4 flex-row-fluid flex-fill">--}}
        {{--                <div class="card-header">--}}
        {{--                    <div class="card-title">--}}
        {{--                        <h6>Customer Details</h6>--}}
        {{--                    </div>--}}
        {{--                </div>--}}

        {{--                <div class="card-body pt-0">--}}
        {{--                    <div class="table-responsive">--}}
        {{--                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">--}}
        {{--                            <tbody>--}}
        {{--                            <tr>--}}
        {{--                                <td>--}}
        {{--                                    <div class="d-flex align-items-center">--}}
        {{--                                        Customer--}}
        {{--                                    </div>--}}
        {{--                                </td>--}}
        {{--                                <td class="fw-bolder text-end">--}}
        {{--                                    <div class="d-flex align-items-center justify-content-end">--}}
        {{--                                        {{ $order->user->first_name }} {{ $order->user->last_name }}--}}
        {{--                                    </div>--}}
        {{--                                </td>--}}
        {{--                            </tr>--}}
        {{--                            <tr>--}}
        {{--                                <td>--}}
        {{--                                    <div class="d-flex align-items-center">--}}
        {{--                                        Email--}}
        {{--                                    </div>--}}
        {{--                                </td>--}}
        {{--                                <td class="fw-bolder text-end">--}}
        {{--                                    {{ $order->user->email }}--}}
        {{--                                </td>--}}
        {{--                            </tr>--}}
        {{--                            <tr>--}}
        {{--                                <td>--}}
        {{--                                    <div class="d-flex align-items-center">--}}
        {{--                                        Phone--}}
        {{--                                    </div>--}}
        {{--                                </td>--}}
        {{--                                <td class="fw-bolder text-end">{{ $order->user->phone }}</td>--}}
        {{--                            </tr>--}}
        {{--                            </tbody>--}}
        {{--                        </table>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        <!--end::Customer details-->

            <!--begin::Shipping address-->
            <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        <h6>Shipping Details</h6>
                    </div>
                </div>
                <div class="card-body pt-1" style="max-width: 450px;">
                    <div class="mb-3">
                        @if (empty($order->shipping_city) && empty($order->shipping_address_line))
                        <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                            No address
                        </p>
                        @else
                        <p id="address_{{ $order->id }}" class="mb-1 t-bold orderShippingName">
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
                        <p class="mb-0"><i class="fas fa-phone-alt"></i> <span class="orderShippingPhone">{{ $order->shipping_phone }}</span></p>
                        @endif

                    </div>
                    @if ($order->status == 'Prescribed' && $order->is_exhausted == 0 && $order->transaction()->count() !== 0)
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

                </div>
            </div>
            <!--end::Shipping address-->

        </div>
        <!--end::Order summary-->

        @if($order->cancellation_request==0)
        <!--begin::Order tracking-->
        <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
            <div class="card-header">
                <div class="card-title">
                    <h6>Order Tracking</h6>
                </div>
            </div>
            <div class="card-body">

            @php
                $orderConfirmed = false;
                $orderConfirmedMsg = 'Payment Pending';
                $doctorConfirmed = false;
                $doctorConfirmedMsg = 'Pending';
                $shippingConfirmed = false;
                $shippingConfirmedMsg = 'Not Delivered';

                $cancelled = false;

                if ($order->payment_status == 'Paid') {
                    $orderConfirmed = true;
                    $orderConfirmedMsg = 'Order Confirmed';

                    if ($order->status == 'Pending') {
                        $doctorConfirmedMsg = 'Awaiting Consultation';
                    }
                    if ($order->status == 'Declined') {
                        $doctorConfirmed = true;
                        $doctorConfirmedMsg = 'Declined';
                        $cancelled = true;
                    }
                    if ($order->status == 'Prescribed') {
                        $doctorConfirmed = true;
                        $doctorConfirmedMsg = 'Prescribed';
                    }
                    if ($order->status == 'Cancelled') {
                        $cancelled = true;
                        $doctorConfirmed = true;
                        $doctorConfirmedMsg = 'Withdrawn';
                        $shippingConfirmed = false;
                        $shippingConfirmedMsg = 'Not Delivered';
                    }
                    if ($orderShipment) {
                        if (strtotime(date('Y-m-d')) >= strtotime($orderShipment->ship_date)) {
                            $shippingConfirmed = true;
                        }
                    }
                }

            @endphp

            <!--large screens-->
                <div class="d-none d-sm-block">
                    <div
                        class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">

                        <div class="step {{ ($orderConfirmed) ? 'completed' : '' }}">
                            <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                            </div>
                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $orderConfirmedMsg }}</h6>
                            <p class="t-small">
                                {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : '-' }}
                            </p>
                        </div>

                        <div
                            class="step {{ ($doctorConfirmed) ? 'completed' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                            <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fas fa-user-md"></i></div>
                            </div>
                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $doctorConfirmedMsg }}</h6>
                            @if($doctorConfirmed)
                                <p class="t-small">
                                    {{ $order->prescribed_date ? \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y') : \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}
                                </p>
                            @endif
                        </div>

                        @if($cancelled)
                            <div class="step order__error">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-times"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">Order Cancelled</h6>
                                <p class="t-small">
                                    {{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') : '-' }}
                                </p>
                            </div>
                        @endif

                        @if(!$cancelled)
                            <div
                                class="step {{ ($shippingConfirmed) ? 'completed' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3 {{ ($shippingConfirmed) ? '' : 'text-muted' }}">Shipped (New Fill)</h6>
                                @if ($shippingConfirmed)
                                    <p class="t-small">{{ \Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y') }}</p>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                <!--mobile-->
                <div class="d-block d-sm-none">
                    <div class="row orderstatus-container">
                        <div class="medium-12 columns">
                            <div class="orderstatus {{ ($orderConfirmed) ? 'done' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-shopping-cart"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    <time>{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : '-' }}</time>
                                    <p>{{ $orderConfirmedMsg }}</p>
                                </div>
                            </div>
                            <div
                                class="orderstatus {{ ($doctorConfirmed) ? 'done' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-user-md"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    @if($doctorConfirmed)
                                        <time>{{ $order->prescribed_date ? \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y') : '-' }}</time>
                                    @endif
                                    <p>{{ $doctorConfirmedMsg }}</p>
                                </div>
                            </div>
                            @if($cancelled)
                                <div class="orderstatus order__error">
                                    <div class="orderstatus-check">
                                        <span class="orderstatus-number"><i class="fas fa-times"></i></span>
                                    </div>
                                    <div class="orderstatus-text">
                                        <time>{{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') : '-' }}</time>
                                        <p>Order Cancelled</p>
                                    </div>
                                </div>
                            @endif
                            @if(!$cancelled)
                                <div
                                    class="orderstatus {{ ($shippingConfirmed) ? 'done' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                                    <div class="orderstatus-check">
                                        <span class="orderstatus-number"><i class="fas fa-shipping-fast"></i></span>
                                    </div>
                                    <div class="orderstatus-text">
                                        @if ($shippingConfirmed)
                                            <time>{{ \Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y') }}</time>
                                        @endif
                                        <p>Shipped (New Fill)</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($orderShipment && $orderShipment->tracking_number)
                    <div class="text-center">
                        <a href="https://www.fedex.com/fedextrack/?trknbr={{ $orderShipment->tracking_number }}"
                           target="_blank" class="t-link">
                            Tracking Number: {{ $orderShipment->tracking_number }}
                        </a>
                    </div>
                @endif

            </div>
        </div>
        <!--end::Order tracking-->
        @endif

        <!--begin::Orders-->
        <div class="d-flex flex-column gap-4 gap-lg-4">
            <div class="d-flex flex-column flex-xl-row gap-2 gap-lg-4">

                <!--begin::Payment Details-->
                @if($order->is_subscription == 0)
                    <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
                        <div class="card-header">
                            <div class="card-title">
                                <h6>Payment Details</h6>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody>
                                    @if($order->payment_status == 'Paid')
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">Transaction ID</div>
                                            </td>
                                            <td class="fw-bolder text-end">{{ $order->transaction_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">Transaction Date</div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @else
                                        <div class="text-center pt-2">Payment Pending</div>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
                        <div class="card-header">
                            <div class="card-title">
                                <h6>Payment Details</h6>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">Order Type</div>
                                        </td>
                                        <td class="fw-bolder text-end">Subscription</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">Transaction ID</div>
                                        </td>
                                        <td class="fw-bolder text-end">{{ $order->transaction_id }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            @endif
            <!--end::Payment Details-->


{{--                @if ($order->plan->is_subscription_based == 0)--}}
{{--                <!--begin::Settings-->--}}
{{--                <div class="card card-flush py-4 flex-row-fluid flex-fill order-action">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-title">--}}
{{--                            <h6>Order Actions</h6>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="card-body pt-0">--}}
{{--                        <div class="d-flex flex-column align-items-start gap-2 py-2">--}}
{{--                            @if($order->payment_status == 'Paid' && $order->invoice)--}}
{{--                                <a href="{{ getImage($order->invoice) }}" target="_blank">--}}
{{--                                    <button class="btn btn-peaks-outline btn-small">View Invoice</button>--}}
{{--                                </a>--}}
{{--                            @endif--}}
{{--                            @if($order->status == 'Pending' && $order->payment_status == 'Paid')--}}
{{--                                <button class="btn btn-peaks-danger btn-small"--}}
{{--                                        onclick="cancelOrder('{{$order->id}}')"--}}
{{--                                        type="button" data-bs-toggle="modal"--}}
{{--                                        data-bs-target="#cancel_modal">--}}
{{--                                    Cancel Order--}}
{{--                                </button>--}}
{{--                            @endif--}}
{{--                            @if($order->status == 'Pending' && $order->payment_status == 'Unpaid' && $order->is_subscription == 0)--}}
{{--                                @php--}}
{{--                                    session()->put('order_id', $order->id);--}}
{{--                                    session()->put('pay_now', true);--}}
{{--                                @endphp--}}
{{--                                <a href="{{ route('make-unpaid-payment',[$order->order_no]) }}">--}}
{{--                                    <button class="btn btn-peaks-outline btn-small">--}}
{{--                                        Pay Now--}}
{{--                                    </button>--}}
{{--                                </a>--}}
{{--                            @else--}}

{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!--end::Settings-->--}}
{{--                @endif--}}
            </div>

            <!--begin::Product List-->
            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h5>Order #{{ 'PC-'.$order->order_no }}</h5>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if ($order->is_subscription == 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-175px">Plan Name</th>
                                    <th class="min-w-100px text-end">Order Type</th>
                                    <th class="min-w-100px text-end">Total</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                                            <img class="product-img align-self-start mt-2" src="{{ !empty($order->product_image) ? getImage($order->product_image) : asset('images/webp/product-img-1.webp') }}"
                                                 alt="">
                                            <div>
                                                {{ $order->product_name }} ({{$order->medicine_variant}})<br>
                                                {{ $order->plan_name }} ({{ $order->plan_title }})<br>
                                                <p class="product_strength">{{$order['product_quantity']}} X {{$order['strength']}}mg</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{ $order->is_subscription ? 'Subscription' : 'One Time' }}
                                    </td>

                                    <td class="text-end">${{ $order->product_price }}</td>
                                </tr>
                                    
                                <tr>
                                    <td colspan="2" class="text-end">Telemedicine Consultation Fee</td>
                                    <td class="text-end">${{ $order->telemedConsult }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Shipping & Handling Cost</td>
                                    <td class="text-end">{!! $order->shippingCost == 0?'<span >FREE</span>':'$'.$order->shippingCost !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">Subtotal</td>
                                    <td class="text-end">
                                        ${{ sprintf('%0.2f',$order->product_price + $order->telemedConsult + $order->shippingCost)}}
                                    </td>
                                </tr>
                                @if(@$order->plan_discount>0)
                                <tr>
                                    <td colspan="2" class="text-end">Plan Discount</td>
                                    <td class="text-end">-${{$order->plan_discount}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="2" class="text-end">Peaks Loyalty Program Member Discount</td>
                                    <td class="text-end">-${{ $order->telemedConsult  }}</td>
                                </tr>

                                 <!--begin::Discount total-->
                                 @if ($order->is_promo_active == true)
                                 <tr>
                                     <td colspan="2" class="text-end">Promo code discount: <span class="t-bold h-color">{{$order->promo_code}} ({{$order->promo_discount_percent}}%)</span>

                                     </td>
                                     <td class="text-end">
                                     -${{ $order->promo_discount }}
                                     </td>
                                    </tr>
                                 @endif
                                 <!--end::Discount total-->
                                <tr>
                                    <td colspan="2" class="text-end"><h4>Grand Total</h4></td>
                                    <td class="text-end"><h4>${{ $order->total_price }}</h4></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-175px">Plan Name</th>
                                    <th class="min-w-100px text-end">Subscribed Date</th>
                                    <th class="min-w-100px text-end">Total</th>
                                </tr>
                                </thead>
                                <tbody class="">
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="product-img mt-2"
                                                 src="{{ !empty($order->product_image) ? getImage($order->product_image) : asset('images/webp/product-img-1.webp') }}" alt="">
                                            <div class="ms-3">
                                                {{ $order->product_name }} ({{$order->medicine_variant}})<br>
                                                {{ $order->plan_name }} ({{ $order->plan_title }})<br>
                                                <p class="product_strength">{{$order['product_quantity']}} X {{$order['strength']}}mg</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}</td>
                                    <td class="text-end">${{ $order->product_price }}</td>
                                </tr>
                                <!--begin::Discount total-->
                                
                                <!--end::Discount total-->
                                <tr>
                                    <td colspan="2" class="text-end">Telemedicine Consultation Fee</td>
                                    <td class="text-end">${{ $order->telemedConsult }}</td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="text-end">Shipping & Handling Cost</td>
                                    <td class="text-end">{!! $order->shippingCost == 0?'<span >FREE</span>':'$'.$order->shippingCost !!}</td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="text-end">Subtotal</td>
                                    <td class="text-end">
                                        ${{ sprintf('%0.2f',$order->product_price + $order->telemedConsult + $order->shippingCost)}}
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2" class="text-end">Peaks Loyalty Program Member Discount</td>
                                    <td class="text-end">-${{ $order->telemedConsult  }}</td>
                                </tr>
                                @if(@$order->plan_discount>0)
                                <tr>
                                    <td colspan="2" class="text-end">Subscribe & Save Discount</td>
                                    <td class="text-end">-${{$order->plan_discount}}</td>
                                </tr>
                                @endif

                                @if ($order->is_promo_active == true)
                                <tr>
                                    <td colspan="2" class="text-end">Promo code discount: <span class="t-bold h-color">{{$order->promo_code}} ({{$order->promo_discount_percent}}%)</span>

                                    </td>
                                    <td class="text-end">
                                    -${{ $order->promo_discount }}
                                    </td>
                                </tr>
                                @endif

                                <tr>
                                    <td colspan="2" class="text-end">
                                        <h4>Grand Total</h4>
                                    </td>
                                    <td class="text-end">
                                        <h4>${{ $order->total_price }}</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>

            <!--begin::Refill History-->
            @include('user.dashboard.my-plan.includes._refill-history')
            <!--end::Refill History-->

            <div class="flex-row-fluid overflow-hidden">
                <div class="card-body py-0">
                    <div
                        class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">

                        @if (!$order->cancellation_request)
                            @if ($order->status == 'Pending' && $order->payment_status == 'Paid')
                                <button class="btn btn-peaks-danger"
                                        onclick="cancelOrder('{{$order->id}}')"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#cancel_modal">
                                    Cancel Order
                                </button>
                            @endif

                            @if ($order->status == 'Pending' && $order->is_subscription == 1 && $order->payment_status == 'Unpaid')
                                <button class="btn btn-peaks-danger"
                                        onclick="cancelOrder('{{$order->id}}')"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#cancel_modal">
                                    Cancel Order
                                </button>
                            @endif
                        @endif

                    </div>
                </div>
            </div>

            <!--begin::Payment History-->
{{--            @if ($order->is_subscription)--}}
{{--                <div class="card card-flush py-4 flex-row-fluid overflow-hidden">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-title">--}}
{{--                            <h5>Payment History</h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body pt-0">--}}

{{--                        <div class="table-responsive">--}}
{{--                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">--}}
{{--                                <thead>--}}
{{--                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">--}}
{{--                                    <th class="min-w-175px">Plan Name</th>--}}
{{--                                    <th class="min-w-175px">Payment Month</th>--}}
{{--                                    <th class="min-w-100px">Payment Date</th>--}}
{{--                                    <th class="min-w-100px">Amount</th>--}}
{{--                                    <th class="min-w-100px">Action</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}

{{--                                @forelse ($order->getTransaction() as $key => $value)--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <div class="d-flex align-items-center">--}}
{{--                                                <div class="fw-bolder text-gray-600 text-hover-primary">--}}
{{--                                                    {{ $order->product_name }}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
{{--                                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format('F') }}</td>--}}
{{--                                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format('M d, Y') }}</td>--}}
{{--                                        <td>${{ $order->total_price }}</td>--}}
{{--                                        <td>--}}
{{--                                            <a href="{{ getImage($value->invoice) }}" target="_blank">--}}
{{--                                                <button class="btn btn-peaks-outline btn-small">View Invoice</button>--}}
{{--                                            </a>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @empty--}}
{{--                                    <tr>--}}
{{--                                        <td class="text-center" colspan="5">No Transaction</td>--}}
{{--                                    </tr>--}}
{{--                                @endforelse--}}
{{--                                <!--end::Orders-->--}}
{{--                                </tbody>--}}
{{--                                <!--end::Table head-->--}}
{{--                            </table>--}}
{{--                            <!--end::Table-->--}}
{{--                        </div>--}}


{{--                    </div>--}}
{{--                    <!--end::Card body-->--}}
{{--                </div>--}}
{{--                <!--end::Payment History-->--}}
{{--            @endif--}}

        </div>
        <!--end::Orders-->
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
                                <option value="Accidentally ordered more than once">Accidentally ordered more than
                                    once
                                </option>
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

    <script type="text/javascript">
     $(document).ready(function () {
        $('#edit_phone').mask('000 000-0000');
     });
    </script>

    <script>
        $(document).ready(function () {
            @if (session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: '<span style="font-size: 22px;">Your order has been cancelled.<span>',
                text: '{{ session()->get('success') }}',
                animation: true,
                showConfirmButton: true,
            }).then((response) => {
                if (response.isConfirmed) {
                    window.location.replace('{{ route('account-orders') }}');
                }
            });
            @endif
            @if(session()->has('error'))
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

@endsection
