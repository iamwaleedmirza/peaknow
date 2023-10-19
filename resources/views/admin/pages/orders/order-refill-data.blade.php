    <div class="d-flex flex-column gap-7 gap-lg-10">

        <!--begin::Order summary-->
        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
            <!--begin::Order details-->
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="currentColor"/>
                                                    <path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="currentColor"/>
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="currentColor"/>
                                                    <path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="currentColor"/>
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
                                        {{ $orderRefill->refill_date ? \Carbon\Carbon::parse($orderRefill->refill_date)->format('M d, Y') : \Carbon\Carbon::parse($orderRefill->created_at)->format('M d, Y') }}
                                    </td>
                                </tr>
                                <!--end::Date-->
                                @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                                <tr>
                                    <td class="">
                                        <div class="d-flex align-items-center text-muted">
                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                            <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z"
                                                        fill="black" />
                                                    <path d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z"
                                                        fill="black" />
                                                    <path
                                                        d="M18 16H17C16.4 16 16 15.6 16 15V13H19V15C19 15.6 18.6 16 18 16Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z"
                                                        fill="black" />
                                                    <path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z" fill="black" />
                                                    <path opacity="0.3"
                                                        d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z"
                                                        fill="black" />
                                                    <path d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z"
                                                        fill="black" />
                                                </svg></span>
                                            <!--end::Svg Icon-->
                                            Refill Status
                                        </div>

                                    </td>
                                    <td class="fw-bolder text-end">
                                        <div class="">
                                            <span class="badge badge-light-danger">Cancelled</span>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="">
                                        <div class="d-flex align-items-center text-muted">
                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                            <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z"
                                                        fill="black" />
                                                    <path d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z"
                                                        fill="black" />
                                                    <path
                                                        d="M18 16H17C16.4 16 16 15.6 16 15V13H19V15C19 15.6 18.6 16 18 16Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z"
                                                        fill="black" />
                                                    <path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z" fill="black" />
                                                    <path opacity="0.3"
                                                        d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z"
                                                        fill="black" />
                                                    <path d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z"
                                                        fill="black" />
                                                </svg></span>
                                            <!--end::Svg Icon-->
                                            Refill Status
                                        </div>

                                    </td>
                                    <td class="fw-bolder text-end">
                                        <div class="">
                                            @if ($orderRefill->refill_status == 'Confirmed')
                                                <span class="badge badge-light-info">Confirmed</span>
                                            @elseif($orderRefill->refill_status == 'Completed')
                                                <span class="badge badge-light-success">Completed</span>
                                            @elseif($orderRefill->refill_status == 'Pending' && $order->status == 'Prescribed')
                                                <span class="badge badge-light-warning">Requested</span>
                                            @elseif($orderRefill->refill_status == 'Pending')
                                                <span class="badge badge-light-warning">Pending</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <div class="d-flex align-items-center text-muted">
                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                            <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z"
                                                        fill="black" />
                                                    <path d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z"
                                                        fill="black" />
                                                    <path
                                                        d="M18 16H17C16.4 16 16 15.6 16 15V13H19V15C19 15.6 18.6 16 18 16Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z"
                                                        fill="black" />
                                                    <path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z" fill="black" />
                                                    <path opacity="0.3"
                                                        d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z"
                                                        fill="black" />
                                                    <path d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z"
                                                        fill="black" />
                                                </svg></span>
                                            <!--end::Svg Icon-->
                                            Refill Shipping Status
                                        </div>

                                    </td>
                                    <td class="fw-bolder text-end">
                                        <div class="">
                                            @if ($orderRefill->is_shipped)
                                                <span class="badge badge-light-success">Shipped</span>
                                            @else
                                                <span class="badge badge-light-warning">Pending</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endif

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card body-->

            </div>
            <!--end::Order details-->

            <!--begin::Shipping address-->
            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                    <img src="{{ asset('admin_assets/assets/media/icons/duotune/ecommerce/ecm006.svg') }}"
                        class="w-125px" />
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Shipping Address</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div>
                        @if (empty($orderRefill->shipping_fullname) && empty($orderRefill->shipping_address_line))
                            <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                                No address
                            </p>
                        @else
                            <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                                {{ $orderRefill->shipping_fullname }}
                            </p>
                            <div class="d-flex">
                                <div>
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="ms-2" style="max-width: 250px;">
                                    <p class="mb-0">{{ $orderRefill->shipping_address_line }}</p>
                                    <p class="mb-0">{{$orderRefill->shipping_address_line2 }}</p>
                                    <p class="mb-1">
                                        {{ $orderRefill->shipping_city . ', ' . $orderRefill->shipping_state . ' - ' . $orderRefill->shipping_zipcode }}</p>
                                    {{-- <p class="mb-1 ">
                                        {{ $order->shipping_address_line }} {{ $order->shipping_address_line2?','.$order->shipping_address_line2.',':'' }}  {{ $order->shipping_city.', '.$order->shipping_state.' - '.$order->shipping_zipcode }}
                                    </p> --}}

                                </div>
                            </div>
                            <p class="mb-0"><i class="fas fa-phone-alt"></i>
                                {{ $order->shipping_phone }}</p>
                        @endif
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Shipping address-->

        </div>


        <!--end::Order summary-->
        <!--begin::Tab content-->
        <div class="tab-content">

            <!--begin::Tab pane-->
            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                <!--begin::Orders-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                        <!--begin::Order tracking-->
                        <div class="card card-flush py-4 flex-row-fluid flex-fill overflow-hidden">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Refill Tracking</h2>
                                </div>
                            </div>
                            <div class="card-body">

                                <!--large screens-->
                                <div class="d-none d-sm-block">
                                    <div
                                        class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">

                                        @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                                        @php
                                            $status = 'Cancelled';
                                        @endphp
                                        <div class="step order__error">
                                            <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                                            </div>
                                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $orderRefill->refill_number == 0 ? 'Refill Requested' : 'Refill Requested' }}</h6>
                                            <p class="t-small">

                                            </p>
                                        </div>
                                        <div class="step completed order__error">
                                            <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fas fa-times"></i></div>
                                            </div>
                                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $orderRefill->refill_number == 0 ? 'Refill '.$status : 'Refill '.$status }}</h6>
                                            <p class="t-small">
                                                    {{  \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}

                                            </p>
                                        </div>
                                        @else
                                        <div class="step {{ $order->status !== 'Pending' ? 'completed' : '' }}">
                                            <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                                            </div>
                                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $orderRefill->refill_number == 0 ? 'Refill Requested' : 'Refill Requested' }}</h6>
                                            <p class="t-small">
                                                {{ $orderRefill->refill_date ? \Carbon\Carbon::parse($orderRefill->refill_date)->format('M d, Y') :  \Carbon\Carbon::parse($orderRefill->created_at)->format('M d, Y') }}

                                            </p>
                                        </div>
                                        <div class="step {{ $orderConfirmed ? 'completed' : '' }}">
                                            <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fas fa-clipboard-check"></i></div>
                                            </div>
                                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $orderRefill->refill_number == 0 ? 'Refill Confirmed' : 'Refill Confirmed' }}</h6>
                                            <p class="t-small">
                                                    {{ $orderRefill->refill_date ? \Carbon\Carbon::parse($orderRefill->refill_date)->format('M d, Y') : '' }}

                                            </p>
                                        </div>
                                        <div class="step {{ $shippingConfirmed ? 'completed' : '' }}">
                                            <div class="step-icon-wrap">
                                                <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
                                            </div>
                                            <h6 class="step-title fw-bold mb-1 mt-3">{{ 'Shipped' }}</h6>
                                            @if ($shippingConfirmed)
                                                <p class="t-small">
                                                    {{ $orderShipment?\Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y'):'' }}
                                                </p>
                                            @endif
                                        </div>

                                        @endif

                                    </div>
                                </div>

                                <!--mobile-->
                                <div class="d-block d-sm-none">
                                    <div class="row orderstatus-container">
                                        <div class="medium-12 columns">

                                            @if ($order->status == 'Declined')
                                            @php
                                                $status = 'Cancelled';
                                            @endphp
                                            <div class="orderstatus done order__error">
                                                <div class="orderstatus-check">
                                                    <span class="orderstatus-number"><i
                                                            class="fas fa-shopping-cart"></i></span>
                                                </div>
                                                <div class="orderstatus-text">
                                                    <time></time>
                                                    <p>{{ $orderRefill->refill_number == 0 ? 'Refill Requested' : 'Refill Requested' }}</p>
                                                </div>
                                            </div>
                                            <div class="orderstatus done order__error">
                                                <div class="orderstatus-check">
                                                    <span class="orderstatus-number"><i class="fas fa-times"></i></span>
                                                </div>
                                                <div class="orderstatus-text">
                                                    <time>   {{  \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}</time>
                                                    <p>{{ $orderRefill->refill_number == 0 ? 'Refill '.$status : 'Refill '.$status }}</p>
                                                </div>
                                            </div>
                                            @else
                                            <div class="orderstatus {{ $orderConfirmed ? 'done' : '' }}">
                                                <div class="orderstatus-check">
                                                    <span class="orderstatus-number"><i class="fas fa-clipboard-check"></i></span>
                                                </div>
                                                <div class="orderstatus-text">
                                                    <time>   {{ $orderRefill->refill_date ? \Carbon\Carbon::parse($orderRefill->refill_date)->format('M d, Y') : '' }}</time>
                                                    <p>{{ $orderRefill->refill_number == 0 ? 'Refill Confirmed' : 'Refill Confirmed' }}</p>
                                                </div>
                                            </div>
                                            <div class="orderstatus {{ $shippingConfirmed ? 'done' : '' }}">
                                                <div class="orderstatus-check">
                                                    <span class="orderstatus-number"><i class="fas fa-shipping-fast"></i></span>
                                                </div>
                                                <div class="orderstatus-text">
                                                    @if ($shippingConfirmed)
                                                        <time> {{ $orderShipment?\Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y'):'' }}</time>
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

                    </div>
                    <!--begin::Order List-->
                    <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Order #PC-{{ $order->order_no }}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="text-start  fw-bolder text-uppercase gs-0">
                                            <th class="min-w-175px">Plan Name</th>
                                            <th class="min-w-100px text-end">Transaction id</th>
{{--                                            <th class="min-w-100px text-end">Order Type</th>--}}
                                            <th class="min-w-100px text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="">
                                        <!--begin::Orders-->
                                        <tr>
                                            <!--begin::Order-->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="symbol symbol-50px">
                                                        <span class="symbol-label"
                                                            style="background-image:url({{ !empty($order->product_image) ? getImage($order->product_image) : asset('images/webp/product-img-1.webp') }});"></span>
                                                    </span>
                                                    <!--begin::Title-->
                                                    <div class="ms-5">
                                                        <span class="fw-bolder text-gray-600 text-hover-primary">
                                                            {{ $order->product_name }} ({{$order->medicine_variant}})<br>
                                                            {{ $order->plan_name }} ({{ $order->plan_title }})<br>
                                                            <p class="product_strength"> {{$order['product_quantity']}} X {{$order['strength']}}mg</p>
                                                        </span>
                                                        {{-- <div class="fs-7 text-muted">Delivery Date: 31/12/2021</div> --}}
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                            </td>
                                            <!--end::Order-->
                                            <td class="text-end">{{ $orderRefill->transaction_id }}</td>

                                            <!--begin::Total-->
                                            <td class="text-end">${{ $order->product_total_price }}</td>
                                            <!--end::Total-->
                                        </tr>
                                        @if ($order->is_promo_active == true)
                                        <tr>
                                            <td colspan="2" class=" text-dark text-end">Promo code discount: <span class="fw-boldest">{{$order->promo_code}} ({{$order->promo_discount_percent}}%)</span>

                                            </td>
                                            <td class="text-dark text-end">
                                            -${{ $order->promo_discount }}
                                            </td>
                                        </tr>
                                        @endif
                                        <!--begin::Grand total-->
                                        <tr>
                                            <td colspan="2" class="fs-3 text-dark text-end">Grand Total</td>
                                            <td class="text-dark fs-3 fw-boldest text-end">
                                                ${{ $order->total_price  }}
                                            </td>
                                        </tr>
                                        <!--end::Grand total-->


                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>

                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order List-->
                </div>
                <!--end::Orders-->
            </div>
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">

            </div>
            <!--end::Tab pane-->
        </div>
        <!--end::Tab content-->
    </div>
