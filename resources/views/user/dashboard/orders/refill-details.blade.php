@extends('user.dashboard.dashboard')

@section('title')
    Order Detail
@endsection

@section('css')
    <link href="{{ asset('/css/peaks/order-tracker.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
@endsection

@section('content')
    <div class="d-flex flex-column gap-4 mb-4">
        <div class="d-flex flex-wrap">
            <h4>Refill Summary</h4>
            <a href="{{ route('account-orders') }}" class="ms-auto me-lg-n7">
                <button class="btn btn-peaks btn-small">Back</button>
            </a>
        </div>

        <div class="d-flex flex-column flex-xl-row gap-4 gap-lg-4">

            <!--begin::Order details-->
            <div class="card card-flush py-4 flex-row-fluid flex-fill">

                <div class="card-header">
                    <div class="card-title">
                        <h6>Refill Details (#{{ 'PC-' . $order->order_no }})</h6>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            Refill Requested On
                                        </div>
                                    </td>
                                    <td class="fw-bolder text-end">
                                        {{ $order->refill_date ? \Carbon\Carbon::parse($order->refill_date)->format('M d, Y') : \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                    </td>
                                </tr>
                                @if ($order->refill_number != 0)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                Refill Number
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">{{ $order->refill_number }} </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                Order Type
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">{{ 'New Fill' }} </td>
                                    </tr>
                                @endif
                                @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                Refill Status
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">
                                            <div>
                                                <span class="status status__danger">Cancelled</span>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                Refill Status
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">
                                            <div>
                                                @if ($order->refill_status == 'Confirmed')
                                                    <span class="status status__info">{{ $order->refill_status }}</span>
                                                @elseif ($order->refill_status == 'Completed')
                                                    <span
                                                        class="status status__success">{{ $order->refill_status }}</span>
                                                @elseif($order->refill_status == 'Pending' && $order->status == 'Prescribed')
                                                    <span class="status status__warning">{{ 'Requested' }}</span>
                                                @elseif($order->refill_status == 'Pending')
                                                    <span class="status status__warning">{{ 'Pending' }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                Shipping Status
                                            </div>
                                        </td>
                                        <td class="fw-bolder text-end">
                                            <div>
                                                @if ($order->is_shipped)
                                                    <span class="status status__success">{{ 'Shipped' }}</span>
                                                @else
                                                    <span class="status status__warning">{{ 'Pending' }}</span>
                                                @endif
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

            <!--begin::Shipping address-->
            <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
                <div class="card-header">
                    <div class="card-title">
                        <h6>Shipping Details</h6>
                    </div>
                </div>
                <div class="card-body pt-1" style="max-width: 450px;">
                    <div>
                        @if (empty($order->shipping_city) && empty($order->shipping_address_line))
                            <p id="address_{{ $order->id }}" class="mb-1 t-bold">No address</p>
                        @else
                            <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                                {{ $order->shipping_fullname }}
                            </p>
                            <div class="d-flex">
                                <div>
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="ms-2">
                                    <p class="mb-0">{{ $order->shipping_address_line }}</p>
                                    <p class="mb-0">{{ $order->shipping_address_line2 }}</p>
                                    <p class="mb-1">
                                        {{ $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zipcode }}
                                    </p>
                                    {{-- <p class="mb-1 ">
                                        {{ $order->shipping_address_line }} {{ $order->shipping_address_line2?','.$order->shipping_address_line2.',':'' }}  {{ $order->shipping_city.', '.$order->shipping_state.' - '.$order->shipping_zipcode }}
                                    </p> --}}

                                </div>
                            </div>
                            <p class="mb-0"><i class="fas fa-phone-alt"></i> {{ $order->shipping_phone }}</p>
                        @endif

                    </div>

                </div>
            </div>
            <!--end::Shipping address-->

        </div>
        <!--end::Order summary-->

        <!--begin::Order tracking-->
        <div class="card py-4 flex-row-fluid flex-fill overflow-hidden">
            <div class="card-header">
                <div class="card-title">
                    <h6>Refill Tracking</h6>
                </div>
            </div>
            <div class="card-body">

                @php
                    $orderConfirmed = false;
                    $shippingConfirmed = false;

                    if ($order->refill_status == 'Confirmed' || $order->refill_status == 'Completed') {
                        $orderConfirmed = true;
                    }
                    if ($order->is_shipped) {
                        $shippingConfirmed = true;
                    }
                @endphp

                <!--large screens-->
                <div class="d-none d-sm-block">
                    <div
                        class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
                        @if ($order->status == 'Declined')
                            @php
                                $status = '';
                                if ($order->status == 'Cancelled') {
                                    $status = 'Cancelled';
                                } else {
                                    $status = 'Cancelled';
                                }
                            @endphp
                            <div class="step order__error">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">
                                    {{ $order->refill_number == 0 ? 'Refill Requested' : 'Refill Requested' }}</h6>
                                <p class="t-small">

                                </p>
                            </div>
                            <div class="step completed order__error">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-times"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">
                                    {{ $order->refill_number == 0 ? 'Refill ' . $status : 'Refill ' . $status }}</h6>
                                <p class="t-small">
                                    {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}

                                </p>
                            </div>
                        @else
                            <div class="step {{ $order->status !== 'Pending' ? 'completed' : '' }}">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">{{ 'Refill Requested' }}</h6>
                                <p class="t-small">
                                    {{ $order->prescribed_date ? \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y') : '' }}
                                </p>
                            </div>

                            <div class="step {{ $orderConfirmed ? 'completed' : '' }}">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-clipboard-check"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">
                                    {{ $order->refill_number == 0 ? 'Refill Confirmed' : 'Refill Confirmed' }}</h6>
                                <p class="t-small">
                                    {{ $order->refill_date ? \Carbon\Carbon::parse($order->refill_date)->format('M d, Y') : '' }}

                                </p>
                            </div>
                            <div class="step {{ $shippingConfirmed ? 'completed' : '' }}">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">{{ 'Shipped' }}</h6>
                                @if ($shippingConfirmed)
                                    <p class="t-small">
                                        {{ $orderShipment ? \Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y') : '' }}
                                    </p>
                                @endif
                            </div>
                        @endif

                    </div>

                </div>
            </div>

            <!--mobile-->
            <div class="d-block d-sm-none">
                <div class="row orderstatus-container">
                    <div class="medium-12 columns">
                        @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                            @php
                                $status = '';
                                if ($order->status == 'Cancelled') {
                                    $status = 'Cancelled';
                                } else {
                                    $status = 'Cancelled';
                                }
                            @endphp
                            <div class="orderstatus done order__error">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-shopping-cart"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    <time></time>
                                    <p>{{ $order->refill_number == 0 ? 'Refill Requested' : 'Refill Requested' }}</p>
                                </div>
                            </div>
                            <div class="orderstatus done order__error">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-times"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    <time> {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}</time>
                                    <p>{{ $order->refill_number == 0 ? 'Refill ' . $status : 'Refill ' . $status }}</p>
                                </div>
                            </div>
                        @else
                            <div class="orderstatus {{ $order->status !== 'Pending' ? 'done' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-shopping-cart"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    <time>
                                        {{ $order->prescribed_date ? \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y') : '' }}
                                    </time>
                                    <p>{{ 'Refill Requested' }}</p>
                                </div>
                            </div>
                            <div class="orderstatus {{ $orderConfirmed ? 'done' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-clipboard-check"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    <time>
                                        {{ $order->refill_date ? \Carbon\Carbon::parse($order->refill_date)->format('M d, Y') : '' }}</time>
                                    <p>{{ $order->refill_number == 0 ? 'Refill Confirmed' : 'Refill Confirmed' }}</p>
                                </div>
                            </div>
                            <div class="orderstatus {{ $shippingConfirmed ? 'done' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-shipping-fast"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    @if ($shippingConfirmed)
                                        <time>
                                            {{ $orderShipment ? \Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y') : '' }}</time>
                                    @endif
                                    <p>{{ 'Shipped' }}</p>
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

    <!--begin::Orders-->
    <div class="d-flex flex-column gap-2 gap-lg-4 mb-4">
        <!--begin::Product List-->
        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h5>Order #{{ 'PC-' . $order->order_no }}</h5>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-175px">Plan Name</th>
                                {{-- <th class="min-w-100px text-end">Order Type</th> --}}
                                <th class="min-w-100px text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                                        <img class="product-img align-self-start mt-2"
                                            src="{{ !empty($order->product_image) ? getImage($order->product_image) : asset('images/webp/product-img-1.webp') }}"
                                            alt="">
                                        <div>
                                            {{ $order->product_name }} ({{$order->medicine_variant}})<br>
                                            {{ $order->plan_name }} ({{ $order->plan_title }})<br>
                                            <p class="product_strength">{{$order['product_quantity']}} X {{$order['strength']}}mg</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end">${{ $order->product_total_price }}</td>
                            </tr>
                            @if ($order->is_promo_active == true)
                            <tr>
                                <td colspan="1" class="text-end">Promo code discount: <span class="t-bold h-color">{{$order->promo_code}} ({{$order->promo_discount_percent}}%)</span>

                                </td>
                                <td class="text-end">
                                -${{ $order->promo_discount }}
                                </td>
                            </tr>
                            @endif
                            
                            <tr>
                                <td colspan="1" class="text-end">
                                    <h4>Grand Total</h4>
                                </td>
                                <td class="text-end">
                                    <h4>${{ $order->total_price }}</h4>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
    <!--end::Orders-->
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                showToast('success', '{{ session()->get('success') }}');
            @endif
            @if (session()->has('error'))
                showToast('error', '{{ session()->get('error') }}');
            @endif
        });
    </script>
@endsection
