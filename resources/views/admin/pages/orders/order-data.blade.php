
<div class="d-flex flex-column gap-7 gap-lg-10">

    <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
        <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-lg-n2 me-auto">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_ecommerce_sales_order_summary">Order Summary</a>
                    </li>
                    <!--end:::Tab item-->
                    <!--begin:::Tab item-->
                    {{-- <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                            href="#kt_ecommerce_sales_order_log_history">Order Log History</a>
                    </li> --}}
                    <!--end:::Tab item-->
                </ul>
        @if(in_array('admin.customers.medical.question.list',$permissions) || Auth::user()->u_type=='superadmin')
            <span class="text-end"><a href="{{url('admin/view-medical-question')}}/{{$order->order_no}}" target="_blank" class="btn btn-primary">View Medical Questions</a></span>
        @endif

        @if($order->cancellation_request==1)
        <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100">
            <!--begin::Icon-->
            <!--begin::Svg Icon | path: icons/duotune/communication/com003.svg-->
            <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="currentColor"></path>
                    <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="currentColor"></path>
                </svg>
            </span>
            <!--end::Svg Icon-->
            <!--end::Icon-->
            <!--begin::Content-->
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="fw-semibold">An order cancellation request has been sent to Beluga on {{ \Carbon\Carbon::parse($order->cancellation_request_date)->format('F j, Y') }}.</h4>
                <span> Upon complete cancellation of the order, a full refund will be issued to customer.</span>
            </div>
            <!--end::Content-->
        </div>
        @endif


        <!--end:::Tabs-->
        <!--begin::Button-->

        @if ($order->payment_status == 'Paid' && $order->status == 'Cancelled' && $order->is_refunded !== 1)
            <a href="#" class="btn btn-sm btn-danger btn-hover-rise " data-bs-toggle="modal"
                data-bs-target="#order_refund_modal" onclick="refundOrder('{{ $order->order_no }}')">Refund</a>
        @elseif($order->status == 'Prescribed')
            {{-- <a href="#" class="btn btn-sm btn-info btn-hover-rise "
                data-bs-toggle="modal" data-bs-target="#"
                onclick="refundOrder('{{ $order->id }}')">Dispatch</a> --}}

        @elseif($order->status == 'Declined' && $order->is_refunded !== 1 && (in_array('admin.refund.order',$permissions) || Auth::user()->u_type=='superadmin'))
            <a href="#" class="btn btn-sm btn-danger btn-hover-rise order_refund_modal_btn" data-bs-toggle="modal"
                data-bs-target="#declined_order_refund_modal" data-order_id="{{ $order->order_no }}"
                data-order_amount="{{ $order->total_price }}">Refund</a>
        @elseif($order->status == 'Pending')
        @endif
        {{-- <a href="{{ URL::previous() }}" class="btn btn-icon btn-light-primary btn-sm ">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr074.svg-->
            <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z"
                        fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </a> --}}
        <!--end::Button-->
        <!--begin::Button-->
        {{-- <a href="../../demo8/dist/apps/ecommerce/sales/edit-order.html" class="btn btn-light-primary btn-sm me-lg-n7">Edit Order</a> --}}
        <!--end::Button-->

    </div>
    @if($order->cancellation_request==1 && ($order->doctor_response=='RX_WRITTEN' || $order->doctor_response=='CONSULT_CONCLUDED'))
    <div class="alert alert-dismissible bg-light-info border border-info border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5">
        <span class="svg-icon svg-icon-2hx svg-icon-info me-4 mb-5 mb-sm-0"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="currentColor"></path><path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.6 8.7 5.6 11 5.1V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V5.1C15.3 5.6 17 7.6 17 10V13C17 14.1 17.9 15 19 15ZM11 10C11 9.4 11.4 9 12 9C12.6 9 13 8.6 13 8C13 7.4 12.6 7 12 7C10.3 7 9 8.3 9 10C9 10.6 9.4 11 10 11C10.6 11 11 10.6 11 10Z" fill="currentColor"></path></svg></span>
        <div class="d-flex flex-column pe-0 pe-sm-10">
            @if($order->doctor_response=='RX_WRITTEN')
                <span class="mt-2 fs-5">An order <strong class="underline">Prescribed</strong> by Beluga on {{ \Carbon\Carbon::parse($order->belugaOrder->rx_written_at)->format('F j, Y') }}</span>
            @elseif($order->doctor_response=='CONSULT_CONCLUDED')
                <span class="mt-2 fs-5">An order <strong class="underline">Declined</strong> by Beluga on {{ \Carbon\Carbon::parse($order->updated_at)->format('F j, Y') }}</span>
            @endif
        </div>
    </div>
    @endif
    <!--begin::Tab content-->
    <div class="tab-content">

        <!--begin::Tab pane-->
        <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">

            <!--begin::Orders-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <!--begin::Order summary-->
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                    <!--begin::Order details-->
                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->

                        @if ($order->is_refunded == 1)
                            <div class="card-header bg-danger">
                                <div class="card-title ">
                                    <h2 class=" text-light-danger">Order Details (#PC-{{ $order->order_no }})</h2>

                                </div>
                                <div class="card-toolbar">
                                    <span class="badge badge-light-danger fs-3">Refunded</span>
                                </div>
                            </div>
                        @else
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Order Details (#PC-{{ $order->order_no }})</h2>
                                </div>
                            </div>
                        @endif


                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody >
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM16 13.5L12.5 13V10C12.5 9.4 12.6 9.5 12 9.5C11.4 9.5 11.5 9.4 11.5 10L11 13L8 13.5C7.4 13.5 7 13.4 7 14C7 14.6 7.4 14.5 8 14.5H11V18C11 18.6 11.4 19 12 19C12.6 19 12.5 18.6 12.5 18V14.5L16 14C16.6 14 17 14.6 17 14C17 13.4 16.6 13.5 16 13.5Z"
                                                                fill="currentColor" />
                                                            <rect x="11" y="19" width="10" height="2" rx="1"
                                                                transform="rotate(-90 11 19)" fill="currentColor" />
                                                            <rect x="7" y="13" width="10" height="2" rx="1"
                                                                fill="currentColor" />
                                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                                                fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Liberty Script Number
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ $order->script_number ?: '-' }} </br>
                                                @if($order->status=='Prescribed' && $order->is_exhausted==0 && $order->cancellation_request==0 && $order->newRefillConfirmed()==0)
                                                    <a href="javascript:void(0)" data-id="{{$order->id}}" class="update-script fs-7">Change Script Number</a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.3" d="M16.163 17.55C17.0515 16.6633 17.6785 15.5488 17.975 14.329C18.2389 13.1884 18.8119 12.1425 19.631 11.306L12.694 4.36902C11.8574 5.18796 10.8115 5.76088 9.67099 6.02502C8.15617 6.3947 6.81277 7.27001 5.86261 8.50635C4.91245 9.74268 4.41238 11.266 4.44501 12.825C4.46196 13.6211 4.31769 14.4125 4.0209 15.1515C3.72412 15.8905 3.28092 16.5617 2.71799 17.125L2.28699 17.556C2.10306 17.7402 1.99976 17.9897 1.99976 18.25C1.99976 18.5103 2.10306 18.7598 2.28699 18.944L5.06201 21.719C5.24614 21.9029 5.49575 22.0062 5.75601 22.0062C6.01627 22.0062 6.26588 21.9029 6.45001 21.719L6.88101 21.288C7.44427 20.725 8.11556 20.2819 8.85452 19.9851C9.59349 19.6883 10.3848 19.5441 11.181 19.561C12.1042 19.58 13.0217 19.4114 13.878 19.0658C14.7343 18.7201 15.5116 18.2046 16.163 17.55Z" fill="currentColor"/>
<path d="M19.631 11.306L12.694 4.36902L14.775 2.28699C14.9591 2.10306 15.2087 1.99976 15.469 1.99976C15.7293 1.99976 15.9789 2.10306 16.163 2.28699L21.713 7.83704C21.8969 8.02117 22.0002 8.27075 22.0002 8.53101C22.0002 8.79126 21.8969 9.04085 21.713 9.22498L19.631 11.306ZM13.041 10.959C12.6427 10.5604 12.1194 10.3112 11.5589 10.2532C10.9985 10.1952 10.4352 10.332 9.96375 10.6405C9.4923 10.949 9.14148 11.4105 8.97034 11.9473C8.79919 12.4841 8.81813 13.0635 9.02399 13.588L2.98099 19.631L4.36899 21.019L10.412 14.975C10.9364 15.1816 11.5161 15.2011 12.0533 15.0303C12.5904 14.8594 13.0523 14.5086 13.361 14.037C13.6697 13.5654 13.8065 13.0018 13.7482 12.4412C13.6899 11.8805 13.4401 11.357 13.041 10.959Z" fill="currentColor"/>
</svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Beluga Visit ID
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ ($order->belugaOrder && $order->belugaOrder->visitId) ? $order->belugaOrder->visitId : '-' }} </br>
                                            </td>
                                        </tr>
                                        <!--end::Date-->
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
                                                    <!--end::Svg Icon-->Ordered Date
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y : h:i A') : '-' }}
                                            </td>
                                        </tr>
                                        <!--end::Date-->
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
                                                    <!--end::Svg Icon-->Order Modified
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y : h:i A') : '-' }}
                                            </td>
                                        </tr>
                                        <!--end::Date-->
                                        <!--begin::Payment method-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z"
                                                                fill="black" />
                                                            <path opacity="0.3"
                                                                d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z"
                                                                fill="black" />
                                                            <path
                                                                d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z"
                                                                fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Payment Status
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">

                                                @if ($order->payment_status == 'Paid')
                                                    <span class="badge badge-success">
                                                        {{ $order->payment_status }}</span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        {{ 'Pending' }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <!--end::Payment method-->
                                        <!--begin::Payment method-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z"
                                                                fill="black" />
                                                            <path opacity="0.3"
                                                                d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z"
                                                                fill="black" />
                                                            <path
                                                                d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z"
                                                                fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Payment Method
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">{{ $order->payment_method }}

                                        </tr>
                                        <!--end::Payment method-->
                                        <tr>
                                            <td >
                                                <div class="d-flex align-items-center text-muted">
                                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z"
                                                                fill="black" />
                                                            <path
                                                                d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z"
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
                                                            <path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z"
                                                                fill="black" />
                                                            <path opacity="0.3"
                                                                d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z"
                                                                fill="black" />
                                                            <path
                                                                d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z"
                                                                fill="black" />
                                                        </svg></span>
                                                    <!--end::Svg Icon-->
                                                    Order Status
                                                </div>

                                            </td>

                                            <td class="fw-bolder text-end">
                                                <div >
                                                    @if ($order->status == 'Cancelled')
                                                        <span class="badge badge-light-danger"> Cancelled</span>
                                                    @elseif($order->status == 'Prescribed')
                                                        <span class="badge badge-light-success">
                                                            {{ $order->status }}</span>
                                                    @elseif($order->status == 'Declined')
                                                        <span class="badge badge-light-danger">
                                                            {{ $order->status }}</span>
                                                    @elseif($order->status == 'Pending')
                                                        <span class="badge badge-light-warning">
                                                            {{ $order->status }}</span>
                                                    @else
                                                        <span class="badge badge-light-danger">
                                                            {{ $order->status }}</span>
                                                    @endif


                                                </div>
                                            </td>
                                        </tr>
                                        @if($order->payment_status=='Paid' && ($order->status=='Cancelled' || $order->cancellation_request==1))
                                        <tr>
                                            <td >
                                                <div class="d-flex align-items-center text-muted">
                                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                                                    <rect x="9" y="13.0283" width="7.3536" height="1.2256" rx="0.6128" transform="rotate(-45 9 13.0283)" fill="currentColor"/>
                                                    <rect x="9.86664" y="7.93359" width="7.3536" height="1.2256" rx="0.6128" transform="rotate(45 9.86664 7.93359)" fill="currentColor"/>
                                                    </svg></span>
                                                    <!--end::Svg Icon-->
                                                    Cancelled By
                                                </div>
                                            </td>
                                            @if($order->is_cancellation_request_by_admin==1)
                                            <td class="fw-bolder text-end">
                                                {{$order->cancellation_request_by_admin_name}}
                                            </td>
                                            @else
                                            <td class="fw-bolder text-end">
                                                User
                                            </td>
                                            @endif
                                        </tr>
                                        @endif
                                        @if($order->status=='Prescribed')
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
                                                    <!--end::Svg Icon-->Prescribed Date
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                {{  \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y : h:i A')}}
                                            </td>
                                        </tr>
                                        @endif
                                        @if ($order->is_refunded == 1)
                                            <tr>
                                                <td >
                                                    <div class="d-flex align-items-center text-muted">
                                                        <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                        <span class="svg-icon svg-icon-2 me-2"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                                    rx="10" fill="black" />
                                                                <path
                                                                    d="M11.276 13.654C11.276 13.2713 11.3367 12.9447 11.458 12.674C11.5887 12.394 11.738 12.1653 11.906 11.988C12.0833 11.8107 12.3167 11.61 12.606 11.386C12.942 11.1247 13.1893 10.896 13.348 10.7C13.5067 10.4947 13.586 10.2427 13.586 9.944C13.586 9.636 13.4833 9.356 13.278 9.104C13.082 8.84267 12.69 8.712 12.102 8.712C11.486 8.712 11.066 8.866 10.842 9.174C10.6273 9.482 10.52 9.82267 10.52 10.196L10.534 10.574H8.826C8.78867 10.3967 8.77 10.2333 8.77 10.084C8.77 9.552 8.90067 9.07133 9.162 8.642C9.42333 8.20333 9.81067 7.858 10.324 7.606C10.8467 7.354 11.4813 7.228 12.228 7.228C13.1987 7.228 13.9687 7.44733 14.538 7.886C15.1073 8.31533 15.392 8.92667 15.392 9.72C15.392 10.168 15.322 10.5507 15.182 10.868C15.042 11.1853 14.874 11.442 14.678 11.638C14.482 11.834 14.2253 12.0533 13.908 12.296C13.544 12.576 13.2733 12.8233 13.096 13.038C12.928 13.2527 12.844 13.528 12.844 13.864V14.326H11.276V13.654ZM11.192 15.222H12.928V17H11.192V15.222Z"
                                                                    fill="black" />
                                                            </svg></span>
                                                        <!--end::Svg Icon-->
                                                        Refunded Transaction Id
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">
                                                    {{ $order->getRefundTransaction() !== null? $order->getRefundTransaction()->transaction_id: 'No Transaction Found!' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    <div class="d-flex align-items-center text-muted">
                                                        <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                        <span class="svg-icon svg-icon-2 me-2"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                                    rx="10" fill="black" />
                                                                <path
                                                                    d="M11.276 13.654C11.276 13.2713 11.3367 12.9447 11.458 12.674C11.5887 12.394 11.738 12.1653 11.906 11.988C12.0833 11.8107 12.3167 11.61 12.606 11.386C12.942 11.1247 13.1893 10.896 13.348 10.7C13.5067 10.4947 13.586 10.2427 13.586 9.944C13.586 9.636 13.4833 9.356 13.278 9.104C13.082 8.84267 12.69 8.712 12.102 8.712C11.486 8.712 11.066 8.866 10.842 9.174C10.6273 9.482 10.52 9.82267 10.52 10.196L10.534 10.574H8.826C8.78867 10.3967 8.77 10.2333 8.77 10.084C8.77 9.552 8.90067 9.07133 9.162 8.642C9.42333 8.20333 9.81067 7.858 10.324 7.606C10.8467 7.354 11.4813 7.228 12.228 7.228C13.1987 7.228 13.9687 7.44733 14.538 7.886C15.1073 8.31533 15.392 8.92667 15.392 9.72C15.392 10.168 15.322 10.5507 15.182 10.868C15.042 11.1853 14.874 11.442 14.678 11.638C14.482 11.834 14.2253 12.0533 13.908 12.296C13.544 12.576 13.2733 12.8233 13.096 13.038C12.928 13.2527 12.844 13.528 12.844 13.864V14.326H11.276V13.654ZM11.192 15.222H12.928V17H11.192V15.222Z"
                                                                    fill="black" />
                                                            </svg></span>
                                                        <!--end::Svg Icon-->
                                                        Refunded Reason
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">
                                                    {{ $order->getRefundTransaction() !== null ? $order->getRefundTransaction()->refund_reason : '-' }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order->is_exhausted == 1)
                                            <tr>
                                                <td >
                                                    <div class="d-flex align-items-center text-muted">
                                                        <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                        <span class="svg-icon svg-icon-2 me-2"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                                    rx="10" fill="black" />
                                                                <path
                                                                    d="M11.276 13.654C11.276 13.2713 11.3367 12.9447 11.458 12.674C11.5887 12.394 11.738 12.1653 11.906 11.988C12.0833 11.8107 12.3167 11.61 12.606 11.386C12.942 11.1247 13.1893 10.896 13.348 10.7C13.5067 10.4947 13.586 10.2427 13.586 9.944C13.586 9.636 13.4833 9.356 13.278 9.104C13.082 8.84267 12.69 8.712 12.102 8.712C11.486 8.712 11.066 8.866 10.842 9.174C10.6273 9.482 10.52 9.82267 10.52 10.196L10.534 10.574H8.826C8.78867 10.3967 8.77 10.2333 8.77 10.084C8.77 9.552 8.90067 9.07133 9.162 8.642C9.42333 8.20333 9.81067 7.858 10.324 7.606C10.8467 7.354 11.4813 7.228 12.228 7.228C13.1987 7.228 13.9687 7.44733 14.538 7.886C15.1073 8.31533 15.392 8.92667 15.392 9.72C15.392 10.168 15.322 10.5507 15.182 10.868C15.042 11.1853 14.874 11.442 14.678 11.638C14.482 11.834 14.2253 12.0533 13.908 12.296C13.544 12.576 13.2733 12.8233 13.096 13.038C12.928 13.2527 12.844 13.528 12.844 13.864V14.326H11.276V13.654ZM11.192 15.222H12.928V17H11.192V15.222Z"
                                                                    fill="black" />
                                                            </svg></span>
                                                        <!--end::Svg Icon-->
                                                        Expired Reason
                                                    </div>
                                                </td>
                                                <td class="fw-bolder text-end">
                                                    @php
                                                        $expiredReason = '-';
                                                        $totalTrans = $order->transaction()->count();
                                                        $currentDate = strtotime(date('Y-m-d'));
                                                        if ($order->refill_until_date) {
                                                            $refillEndDate = $order->refill_until_date;
                                                            if ($currentDate >= strtotime($refillEndDate)) {
                                                                $expiredReason = 'Prescription Expired';
                                                            }
                                                        }
                                                        if ($totalTrans >= 6) {
                                                            $expiredReason = '6 Refills Filled';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="badge badge-light-info mx-3">{{ $expiredReason }}</span>
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
                    <!--begin::Customer details-->
                    <div class="card card-flush flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Customer Details</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody >
                                        <!--begin::Customer ID-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                                rx="10" fill="currentColor" />
                                                            <rect x="11" y="17" width="7" height="2" rx="1"
                                                                transform="rotate(-90 11 17)" fill="currentColor" />
                                                            <rect x="11" y="9" width="2" height="2" rx="1"
                                                                transform="rotate(-90 11 9)" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Customer ID
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <a href="{{ route('admin.customers.view', [$order->user->id]) }}"
                                                    class="text-gray-600 text-hover-primary"> {{ $order->user->id }}</a>

                                            </td>
                                        </tr>
                                        <!--end::Customer ID-->
                                        <!--begin::Liberty ID-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                                rx="10" fill="currentColor" />
                                                            <rect x="11" y="17" width="7" height="2" rx="1"
                                                                transform="rotate(-90 11 17)" fill="currentColor" />
                                                            <rect x="11" y="9" width="2" height="2" rx="1"
                                                                transform="rotate(-90 11 9)" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Liberty Patient ID
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                @if ($order->user->liberty_patient_id)
                                                <a href="{{ route('admin.customers.view', [$order->user->id]) }}"
                                                    class="text-gray-600 text-hover-primary"> {{ $order->user->liberty_patient_id}}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <!--end::Liberty ID-->
                                        <!--begin::Customer name-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                                fill="black" />
                                                            <path
                                                                d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                                fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Customer
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <div class="d-flex align-items-center justify-content-end">

                                                    <!--begin::Name-->
                                                    <a href="{{ route('admin.customers.view', [$order->user->id]) }}"
                                                        class="text-gray-600 text-hover-primary">{{ $order->user->first_name }}
                                                        {{ $order->user->last_name }}</a>
                                                    <!--end::Name-->
                                                </div>
                                            </td>
                                        </tr>
                                        <!--end::Customer name-->
                                        <!--begin::Customer email-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                                fill="black" />
                                                            <path
                                                                d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                                fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Email
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <a href="{{ route('admin.customers.view', [$order->user->id]) }}"
                                                    class="text-gray-600 text-hover-primary">{{ $order->user->email }}</a>
                                            </td>
                                        </tr>
                                        <!--end::Payment method-->
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                    <span class="svg-icon svg-icon-2 me-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z"
                                                                fill="black" />
                                                            <path opacity="0.3" d="M19 4H5V20H19V4Z" fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->Phone
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">{{ $order->user->phone }}</td>
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
                    <!--end::Customer details-->
                    <!--begin::Settings-->

                    <!--end::Settings-->
                </div>
                <!--end::Order summary-->
                <!--begin::Order Tracking view-->
                @include('admin.pages.orders.order-tracking-view')
                <!--end::Order Tracking view-->
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">

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
                                @if (empty($order->shipping_city) && empty($order->shipping_address_line))
                                    <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                                        No address
                                    </p>
                                @else
                                    <p id="address_{{ $order->id }}" class="mb-1 t-bold">
                                        {{ $order->shipping_fullname }}
                                    </p>
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="ms-2" style="max-width: 250px;">

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
                                    <p class="mb-0"><i class="fas fa-phone-alt"></i>
                                        {{ $order->shipping_phone }}</p>
                                @endif
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Shipping address-->
                    @if ($order->is_subscription == 0)
                        <!--begin::Payment Details-->
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Background-->
                            <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                                <img src="{{ asset('admin_assets/assets/media/icons/duotune/finance/fin002.svg') }}"
                                    class="w-125px" />
                            </div>
                            <!--end::Background-->
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Payment Details</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            @php
                                $content = '';

                                if ($order->is_subscription) {
                                    foreach ($order->getTransaction() as $key => $value) {
                                        $content .=
                                            '<span><strong>Payment Type</strong> : Subscription Pay</span>
                                                                    <br>
                                                                    <span><strong>Transaction ID</strong> : ' .
                                            $value->transaction_id .
                                            '</span>
                                                                    <br>
                                                                    <span><strong>Subscribed Date</strong> : ' .
                                            \Carbon\Carbon::parse($value->updated_at)->format('m/d/Y : h:i A') .
                                            '</span><br>';
                                    }

                                    empty($content) ? ($content = 'Didn\'t Paid Yet') : $content;
                                } else {
                                    $content =
                                        '<span><strong>Payment Type</strong> : One Time Pay</span>
                                                                                                                                                                                                                        <br>
                                                                                                                                                                                                                        <span><strong>Transaction ID</strong> : ' .
                                        $order->transaction_id .
                                        '</span>
                                                                                                                                                                                                                        <br>
                                                                                                                                                                                                                        <span><strong>Transaction Date</strong> : ' .
                                        \Carbon\Carbon::parse($order->updated_at)->format('M d, Y : h:i A') .
                                        '</span>';
                                    empty($order->transaction_id) ? ($content = 'Didn\'t Paid Yet') : $content;
                                }

                            @endphp
                            <div class="card-body pt-0">{!! $order->is_subscription == 0 ? $content : '<div style="height:60px;overflow: scroll;">' . $content . '</div>' !!}</div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Payment Details-->
                    @else
                        <!--begin::Payment Details-->
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Background-->
                            <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                                <img src="{{ asset('admin_assets/assets/media/icons/duotune/finance/fin002.svg') }}"
                                    class="w-125px" />
                            </div>
                            <!--end::Background-->
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Subscription Details</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            @php

                            @endphp
                            <div class="card-body pt-0">
                                <div class="d-flex align-items-center text-bold mb-2">
                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                    <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z"
                                                fill="black" />
                                            <path opacity="0.3"
                                                d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z"
                                                fill="black" />
                                        </svg></span>
                                    <!--end::Svg Icon-->
                                    Subscription Status:
                                    @if ($order->subscription && $order->subscription->is_paused == '0')
                                        @if ($order->is_active)
                                            <span class="badge badge-light-success mx-3"> Active</span>
                                        @else
                                            <span class="badge badge-light-danger mx-3"> Inactive</span>
                                        @endif
                                    @else
                                        <span class="badge badge-light-warning mx-3"> Paused</span>
                                    @endif

                                </div>
                                @if ($order->subscription->is_paused)
                                    @php
                                        $pausedAt = ($order->subscriptionLogs()->where('status', 'PAUSED')->latest()->first('created_at'))->created_at ?? null;
                                    @endphp
                                    @if(!empty($pausedAt))
                                        <div class="d-flex align-items-center text-bold mb-2">
                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                            <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z" fill="black" /><path opacity="0.3" d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z" fill="black" /></svg></span>
                                            <!--end::Svg Icon-->
                                            Paused at:
                                            {{ \Carbon\Carbon::parse()->format('M d, Y : h:i A') ?? 'None' }}
                                        </div>
                                    @endif
                                @endif
                                <div class="d-flex align-items-center text-bold mb-2">
                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                    <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z"
                                                fill="black" />
                                            <path opacity="0.3"
                                                d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z"
                                                fill="black" />
                                        </svg></span>
                                    <!--end::Svg Icon-->
                                    Start Date:
                                    {{ $order->subscription ? \Carbon\Carbon::parse($order->subscription->start_date)->format('M d, Y : h:i A') : 'None' }}
                                </div>

                                @if ($order->subscription && $order->subscription->is_paused == '0')
                                    @if (!empty($order->subscription->next_refill_date) && $order->is_active==1)
                                        <div class="d-flex align-items-center text-bold mb-2">
                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                            <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z" fill="black" /><path opacity="0.3" d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z" fill="black" /></svg></span>
                                            <!--end::Svg Icon-->
                                            Next refill date:
                                            {{ \Carbon\Carbon::parse($order->subscription->next_refill_date)->format('M d, Y') ?? 'None' }}
                                        </div>
                                    @endif
                                @endif

                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Payment Details-->

                    @endif
                    <!--begin::Response-->
                    @if ($order->status == 'Cancelled')
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Background-->
                            <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                                <img src="{{ asset('admin_assets/assets/media/icons/duotune/arrows/arr061.svg') }}"
                                    class="w-125px" />
                            </div>
                            <div class="card-body pt-0 card-body-margin" style="max-width: 250px;">
                                <p class="cancellation_date"><strong>Reason:</strong></p>
                                {{ $order->cancel_reason }}
                            </div>

                            <div class="card-body pt-0">
                                <p style="max-width: 250px;" class="cancellation_date"><strong>Cancelled Date:</strong></p>
                                {{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y : h:i A') : '-' }}
                            </div>

                            <!--end::Card body-->
                        </div>
                    @else
                        <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
                            <!--begin::Background-->
                            <div class="position-absolute top-0 end-0 opacity-10 pe-none text-end">
                                <img src="{{ asset('admin_assets/assets/media/icons/duotune//medicine/med001.svg') }}"
                                    class="w-125px" />
                            </div>
                            <!--end::Background-->
                            <!--begin::Card header-->
                            @if($order->cancellation_request==1)
                                @if($order->doctor_response!='')
                                    <div class="card-header" style="min-height: 24px;">
                                        <div class="card-title">
                                            <h5>Doctor Response</h5>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    {!! $order->doctor_response ? '' : '<div class="card-body pt-0">No Doctor Response</div>' !!}
                                @endif
                            @else
                                <div class="card-header" style="min-height: 24px;">
                                    <div class="card-title">
                                        <h5>Doctor Response</h5>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                {!! $order->doctor_response ? '' : '<div class="card-body pt-0">No Doctor Response</div>' !!}
                            @endif
                            
                            
                            @if($order->cancellation_request==1)
                            <div class="card-body pt-0">
                                <p style="max-width: 250px;" class="cancellation_date"><strong>Date of cancellation request</strong></p>
                                {{ \Carbon\Carbon::parse($order->cancellation_request_date)->format('M d, Y h:m A') ?? 'None' }}
                            </div>
                            @endif
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>{{ $order->doctor_name ? $order->doctor_name : '' }}</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <p style="max-width: 250px;">
                                    {!! $order->doctor_response ? '<strong>Response</strong> : ' . $order->doctor_response : '' !!}
                                </p>
                            </div>
                            
                            <!--end::Card body-->
                        </div>
                    @endif

                    <!--end::Response-->
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
                        @if ($order->is_subscription == 0)
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="text-start  fw-bolder text-uppercase gs-0">
                                            <th class="min-w-175px">Plan Name</th>
                                            <th class="min-w-100px text-end">Order Type</th>
                                            <th class="min-w-100px text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >
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
                                            <!--begin::SKU-->
                                            <td class="text-end pe-0">
                                                {{ $order->is_subscription ? 'Subscription Pay' : 'One Time Pay' }}
                                            </td>
                                            <!--end::SKU-->

                                            <!--begin::Total-->
                                            <td class="text-end">${{ $order->product_price }}</td>
                                            <!--end::Total-->
                                        </tr>
                                        <!--end::Orders-->
                                        
                                        <!--begin::Telemedicine Consult-->
                                        <tr>
                                            <td colspan="2" class="text-end">Telemedicine Consultation Fee</td>
                                            <td class="text-end">${{ $order->telemedConsult }}</td>
                                        </tr>
                                        <!--end::Telemedicine Consult-->

                                         <!--begin::Shipping-->
                                        <tr>
                                            <td colspan="2" class="text-end">Shipping Cost</td>
                                            <td class="text-end">{!! $order->shippingCost == 0?'<span class="text-success">FREE</span>':'$'.$order->shippingCost !!}</td>
                                        </tr>
                                        <!--end::Shipping-->

                                        <!--begin::Subtotal-->
                                        <tr>
                                            <td colspan="2" class="text-end">Subtotal</td>
                                            <td class="text-end">${{ sprintf('%0.2f',$order->product_price + $order->telemedConsult + $order->shippingCost)}}</td>
                                        </tr>
                                        <!--end::Subtotal-->

                                        <!--begin::discount-->
                                        <tr>
                                            <td colspan="2" class="text-end">Peaks Loyalty Program Member
                                                Discount
                                            </td>
                                            <td class="text-end">-${{ $order->telemedConsult }}</td>
                                        </tr>
                                        <!--end::discount-->

                                        <!--begin::Discount total-->
                                        @if ($order->is_promo_active == true)
                                        <tr>
                                            <td colspan="2" class=" text-dark text-end">Promo code discount: <span class="fw-boldest">{{$order->promo_code}} ({{$order->promo_discount_percent}}%)</span>

                                            </td>
                                            <td class="text-dark text-end">
                                            -${{ $order->promo_discount }}
                                            </td>
                                        </tr>
                                        @endif
                                        <!--end::Discount total-->

                                        <!--begin::Grand total-->
                                        <tr>
                                            <td colspan="2" class="fs-3 text-dark text-end">Grand Total</td>
                                            <td class="text-dark fs-3 fw-boldest text-end">
                                                {{-- ${{ $order->total_price + $order->telemedConsult }} --}}
                                                ${{ $order->total_price }}
                                            </td>
                                        </tr>
                                        <!--end::Grand total-->
                                        @if ($order->is_refunded == '1')
                                            <!--begin::Refund total-->
                                            @php
                                                $refundData = $order->getRefundTransaction();
                                            @endphp

                                            <tr>
                                                <td colspan="2" class="fs-3 text-danger fw-boldest text-end">Total
                                                    Refunded Amount</td>
                                                <td class="text-danger fs-3 fw-boldest text-end">
                                                    ${{ $refundData ? $refundData->amount : $order->total_price }}
                                                </td>
                                            </tr>
                                            <!--end::Refund total-->
                                        @endif

                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>
                        @else
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="text-start  fw-bolder text-uppercase gs-0">
                                            <th class="min-w-175px">Plan Name</th>
                                            <th class="min-w-100px text-end">Order Type</th>
                                            <th class="min-w-100px text-end">Subscribed Date</th>
                                            <th class="min-w-100px text-end">Total</th>

                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >

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

                                            <!--begin::SKU-->
                                            <td class="text-end pe-0">
                                                {{ $order->is_subscription ? 'Subscription Pay' : 'One Time Pay' }}
                                            </td>
                                            <!--end::SKU-->

                                            <td class="text-end">
                                                {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y : h:i A') }}
                                            </td>
                                            <!--begin::Total-->
                                            <td class="text-end">${{ $order->product_price }}</td>
                                            <!--end::Total-->

                                        </tr>
                                        <!--end::Orders-->
                                        <!--begin::Discount total-->
                                        
                                        <!--begin::Telemedicine Consult-->
                                        <tr>
                                            <td colspan="3" class="text-end">Telemedicine Consultation Fee</td>
                                            <td class="text-end">${{ $order->telemedConsult }}</td>
                                        </tr>
                                        <!--end::Telemedicine Consult-->

                                        <!--begin::Shipping-->
                                        <tr>
                                            <td colspan="3" class="text-end">Shipping & Handling Cost</td>
                                            <td class="text-end">{!! $order->shippingCost == 0?'<span style="color: #096036;">FREE</span>':'$'.$order->shippingCost !!}</td>
                                        </tr>
                                        <!--end::Shipping-->

                                        <!--begin::Subtotal-->
                                        <tr>
                                            <td colspan="3" class="text-end">Subtotal</td>
                                            <td class="text-end">
                                                ${{ sprintf('%0.2f',$order->product_price + $order->telemedConsult + $order->shippingCost)}}
                                            </td>
                                        </tr>
                                        <!--end::Subtotal-->

                                        <!--begin::discount-->
                                        <tr>
                                            <td colspan="3" class="text-end">Peaks Loyalty Program Member
                                                Discount</td>
                                            <td class="text-end">
                                                -${{ $order->telemedConsult }}
                                            </td>
                                        </tr>
                                        <!--end::discount-->

                                        @if($order->plan_discount>0)
                                        <tr>
                                            <td colspan="3" class="text-end">Subscribe & Save Discount</td>
                                            <td class="text-end">-${{$order->plan_discount}}</td>
                                        </tr>
                                        @endif
                                        <!--begin::Grand total-->
                                        {{-- <tr> --}}
                                        {{-- <td colspan="3" class="fs-3 text-dark text-end">Order Total</td> --}}
                                        {{-- <td class="text-dark fs-3 fw-boldest text-end"> --}}
                                        {{-- ${{ $order->total_price + $order->shippingCost }} --}}
                                        {{-- </td> --}}
                                        {{-- </tr> --}}
                                        <!--end::Grand total-->

                                        

                                        @if ($order->is_promo_active == true)
                                        <tr>
                                            <td colspan="3" class=" text-dark text-end">Promo code discount: <span class="fw-boldest">{{$order->promo_code}} ({{$order->promo_discount_percent}}%)</span>

                                            </td>
                                            <td class="text-dark text-end">
                                            -${{ $order->promo_discount }}
                                            </td>
                                        </tr>
                                        @endif
                                        <!--end::Discount total-->

                                        <!--begin::Grand total-->
                                        <tr>
                                            <td colspan="3" class="fs-3 text-dark text-end">Grand Total</td>
                                            <td class="text-dark fs-3 fw-boldest text-end">
                                                {{-- ${{ $order->total_price + $order->telemedConsult }} --}}
                                                ${{ $order->total_price }}
                                            </td>
                                        </tr>
                                        <!--end::Grand total-->
                                        @if ($order->is_refunded == '1')
                                            <!--begin::Refund total-->
                                            @php
                                                $refundData = $order->getRefundTransaction();
                                            @endphp

                                            <tr>
                                                <td colspan="3" class="fs-3 text-danger fw-boldest text-end">Total
                                                    Refunded Amount</td>
                                                <td class="text-danger fs-3 fw-boldest text-end">
                                                    ${{ $refundData ? $refundData->amount : $order->total_price }}
                                                </td>
                                            </tr>
                                            <!--end::Refund total-->
                                        @endif
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>

                        @endif

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Order List-->
                @if ($order->transaction()->count() !== 0)
                    <!--begin::Order tracking-->
                    @include(
                        'admin.pages.orders.includes._refill-tracking'
                    )
                    <!--end::Order tracking-->
                @endif
                <!--begin::Refill History-->
                <div id="orderRefillLoader" class="card card-flush py-4 flex-row-fluid overflow-hidden">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Refill History</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="refill_history_tbl">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start fw-bolder text-uppercase gs-0">
                                        <th >Refill NO</th>
                                        <th >Refill Status</th>
                                        <th >Refill Date</th>
                                        <th class="">Transaction ID</th>
                                        <th class="">Total Amount</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody >

                                    <!--begin::Orders-->
                                    @forelse ($order->orderRefill()->orderBy('created_at', 'DESC')->get() as $key => $value)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a  href="javascript:void(0)" class="menu-link"
                                                    id="view-refill-track"
                                                    data-uri="{{ route('admin.order.refill.data', [$order->id, $value->refill_number]) }}">
                                                    <div class="view-order-details">
                                                        {{ $value->refill_number == 0 ? 'New Fill' : 'Refill ' . $value->refill_number }}
                                                    </div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                                                    <span class="badge badge-light-danger">Cancelled</span>
                                                @else
                                                    @if ($value->refill_status == 'Confirmed')
                                                        <span class="badge badge-light-info">Confirmed</span>
                                                    @elseif ($value->refill_status == 'Completed')
                                                        <span class="badge badge-light-success">Completed</span>
                                                    @elseif($value->refill_status == 'Pending' && $order->status == 'Prescribed')
                                                        <span class="badge badge-light-warning">Requested</span>
                                                    @elseif ($value->refill_status == 'Pending')
                                                        <span class="badge badge-light-warning">Pending</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Cancelled</span>
                                                    @endif
                                                @endif

                                            </td>
                                            <td>{{$value->refill_date?\Carbon\Carbon::parse($value->refill_date)->format('M d, Y'):\Carbon\Carbon::parse($value->created_at)->format('M d, Y h:i A')}}
                                            </td>
                                            <td class="">{{ $value->transaction_id }}</td>
                                            <td class="">${{ $order->total_price }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                                    data-kt-menu-trigger="click"
                                                    data-kt-menu-placement="bottom-end">Actions
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                    <span class="svg-icon svg-icon-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-250px  py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:void(0)" class="menu-link px-3"
                                                            id="view-refill"
                                                            data-uri="{{ route('admin.order.refill.data', [$order->id, $value->refill_number]) }}">View</a>

                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->

                                                    @if ($value->is_shipped == true && (in_array('admin.order.refill.tracking.data.post',$permissions) || Auth::user()->u_type=='superadmin'))
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:void(0)" class="menu-link px-3"
                                                                id="view-refill-tracking"
                                                                data-uri="{{ route('admin.order.refill.tracking.data', [$order->id, $value->refill_number]) }}">Update
                                                                Tracking Number</a>
                                                        </div>
                                                    @endif


                                                    @if (($value->refill_status == 'Confirmed' && $value->is_shipped == 0) && (in_array('admin.update.refill.ship.detail',$permissions) || Auth::user()->u_type=='superadmin'))
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:void(0)" class="menu-link px-3 mark_as_shipped_modal" data-id="{{$value->id}}">Mark as Shipped</a>
                                                        </div>
                                                    @endif

                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->

                                                    <div class="menu-item px-3">
                                                        <a href="{{ getImage($value->invoice) }}" target="_blank"
                                                            class="menu-link px-3">
                                                            Invoice
                                                        </a>
                                                    </div>

                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->



                                                {{-- <a href="{{ route('admin.view.order.refill', [$order->id, $value->refill_number,'uri_redirect' => $urlName]) }}"
                                                    target="_blank">
                                                    <button class="btn btn-primary btn-small">View</button>
                                                </a> --}}

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">No Refill History</td>
                                        </tr>
                                    @endforelse
                                    <!--end::Orders-->
                                </tbody>
                                <!--end::Table head-->
                            </table>
                            <!--end::Table-->
                        </div>


                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Refill History-->
                @if ($order->orderRefundHistory()->where('refill_number', '!=', 0)->count() !== 0)
                    <!--begin::Refund History-->
                    <div id="" class="card card-flush py-4 flex-row-fluid overflow-hidden">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Refund History</h2>
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
                                        <tr class="text-start fw-bolder text-uppercase gs-0">
                                            <th >Refill NO</th>
                                            <th >Refund Amount</th>
                                            <th >Refund Reason</th>
                                            <th class="min-w-100px text-end">Transaction ID</th>
                                            <th >Refund Date</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >

                                        <!--begin::Orders-->
                                        @forelse ($order->orderRefundHistory()->where('refill_number','!=',0)->orderBy('created_at', 'DESC')->get() as $key => $value)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-dark text-hover-primary">
                                                            {{ $value->refill_number == 0 ? 'New Fill' : 'Refill ' . $value->refill_number }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td >${{ $value->amount }}</td>
                                                <td >{{ $value->refund_reason ?: '-' }}</td>
                                                <td class="text-end">{{ $value->transaction_id }}</td>

                                                <td>{{ \Carbon\Carbon::parse($value->created_at)->format('M d, Y : h:i A') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">No Transaction</td>
                                            </tr>
                                        @endforelse
                                        <!--end::Orders-->
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>


                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Refund History-->
                @endif

                @if ($order->is_subscription && $order->subscriptionLogs()->count() !== 0)
                    <!--begin::Subscription Logs-->
                    <div id="" class="card card-flush py-4 flex-row-fluid overflow-hidden">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Subscription Log</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="subscriptionLogTable" class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                    <tr class="text-start fw-bolder text-uppercase gs-0">
                                        <th>Log Status</th>
                                        <th>Action by</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >

                                    <!--begin::Orders-->
                                    @forelse ($order->subscriptionLogs()->orderBy('created_at', 'DESC')->get() as $key => $value)
                                        <tr>
                                            <td>
                                                @if ($value->status == 'PAUSED')
                                                    <div class="badge badge-light-warning">{{ $value->status ?? 'None' }}</div>
                                                @else
                                                    <div class="badge badge-light-primary">{{ $value->status ?? 'None' }}</div>
                                                @endif
                                            </td>
                                            <td >{{ $value->updated_by ?? 'None' }}</td>
                                            <td >{{ ($value->username!='') ? $value->username : '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('M d, Y : h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">No Logs</td>
                                        </tr>
                                    @endforelse
                                    <!--end::Orders-->
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>


                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Subscription Logs-->
                @endif

            @if ($order->paymentLogs()->count() !== 0)
                <!--begin::Subscription Logs-->
                    <div id="" class="card card-flush py-4 flex-row-fluid overflow-hidden">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Payment Log</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="paymentLogTable" class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                    <tr class="text-start fw-bolder text-uppercase gs-0">
                                        <th>Payment For</th>
                                        <th>Event</th>
                                        <th>Status</th>
                                        <th>Transaction Message</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >

                                    <!--begin::Orders-->
                                    @forelse ($order->paymentLogs()->orderBy('id', 'DESC')->get() as $key => $value)
                                        <tr>
                                            <td><div class="badge badge-light-info">{{ $value->payment_for ?? 'None' }}</div></td>
                                            <td><div class="badge badge-light-primary">{{ $value->event_type ?? 'None' }}</div></td>
                                            <td><div class="badge {{ $value->status == 'SUCCESS' ? 'badge-light-success' : 'badge-light-danger' }}">{{ $value->status ?? 'None' }}</div></td>
                                            <td>{{ $value->transaction_message ?? 'None' }}</td>
                                            <td>{{ $value->transaction_id ?? 'None' }}</td>
                                            <td>{{ '$'.$value->amount ?? 'None' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('M d, Y : h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">No Logs</td>
                                        </tr>
                                    @endforelse
                                    <!--end::Orders-->
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>


                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Subscription Logs-->
                @endif

            </div>
            <!--end::Orders-->
        </div>
        <!--end::Tab pane-->

    </div>
    <!--end::Tab content-->

</div>
<script>
    var targetOrderRefillView = document.querySelector("#orderRefillLoader");
    var blockUIOrderRefillView = new KTBlockUI(targetOrderRefillView, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
    });
    $("#order_logs").DataTable({
    "language": {
    "lengthMenu": "Show _MENU_",
    },
    "dom":
    "<'row'" +
    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
    ">" +

    "<'table-responsive'tr>" +

    "<'row'" +
    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
    ">"
    });

    // $('#subscriptionLogTable').DataTable({
    //     "scrollY": "250px",
    //     "scrollCollapse": true,
    //     "paging": false,
    //     'ordering': false,
    //     "dom": "<'table-responsive'tr>"
    // });

    $("#refill_history_tbl,#paymentLogTable,#subscriptionLogTable").DataTable({order:[]});
</script>
