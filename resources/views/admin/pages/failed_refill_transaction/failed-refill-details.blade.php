@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Order View')
@section('css')
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <style>
        .step-icon > i{
            font-size: 38px !important;
        }
        .step-refill-track > i{
            font-size: 25px !important;
        }
        .steps .step.completed .step-icon > i {
            color: #fff !important;
        }
        @media screen and (max-width: 575px) {
            .orderstatus-check i {
                 font-size: 22px !important;
            }
            .done .orderstatus-check i {
                color: #fff !important;
            }
        }
    </style>
    <!--end::Page Vendor Stylesheets-->
@endsection
@section('navbar')
    @include('admin.includes.sidebar')
@endsection

@section('header')
    @include('admin.includes.header')
@endsection
@if (URL::previous() == route('admin.login'))
<script>window.location = "{{route('admin.home')}}";</script>
@endif
@section('content')

<ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-bold mb-4">
<li class="breadcrumb-item pe-3"><a href="{{route('admin.home')}}" class="pe-3">Home</a></li>
<li class="breadcrumb-item pe-3"><a href="{{route('admin.failed.refill.transaction')}}" class="uri-redirect pe-3">Failed Refill Transactions</a></li>
<li class="breadcrumb-item px-3 text-muted">Refill Transaction Details</li>
</ol>
<!--begin::Order details page-->
    <div id="order_details">
        <div class="d-flex flex-column gap-7 gap-lg-10">

            <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
                <!--begin:::Tabs-->

                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-lg-n2 me-auto">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_ecommerce_sales_order_summary">Refill Transaction Details</a>
                    </li>
                    <!--end:::Tab item-->

                </ul>
            </div>


            <!--end::Order summary-->
            <!--begin::Tab content-->
            <div class="tab-content">

                <!--begin::Tab pane-->
                <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                    <!--begin::Orders-->
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                            <!--begin::Order details-->
                            <div class="card card-flush  flex-row-fluid">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <a href="{{url('')}}/admin/{{$order->order->id}}/order-detail" target="_blank"><h2>Order Details <span class="failed-order"> (#PC-{{$order->order_no}})</span></h2></a>
                                    </div>
                                </div>
                                

                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                            <!--begin::Table body-->
                                            <tbody>
                                                <!--end::Date-->
                                                <!--begin::Date-->
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->
                                                            <span class="svg-icon svg-icon-2 me-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                                                    <path opacity="0.3" d="M19 3.40002C18.4 3.40002 18 3.80002 18 4.40002V8.40002H14V4.40002C14 3.80002 13.6 3.40002 13 3.40002C12.4 3.40002 12 3.80002 12 4.40002V8.40002H8V4.40002C8 3.80002 7.6 3.40002 7 3.40002C6.4 3.40002 6 3.80002 6 4.40002V8.40002H2V4.40002C2 3.80002 1.6 3.40002 1 3.40002C0.4 3.40002 0 3.80002 0 4.40002V19.4C0 20 0.4 20.4 1 20.4H19C19.6 20.4 20 20 20 19.4V4.40002C20 3.80002 19.6 3.40002 19 3.40002ZM18 10.4V13.4H14V10.4H18ZM12 10.4V13.4H8V10.4H12ZM12 15.4V18.4H8V15.4H12ZM6 10.4V13.4H2V10.4H6ZM2 15.4H6V18.4H2V15.4ZM14 18.4V15.4H18V18.4H14Z" fill="black"></path>
                                                                    <path d="M19 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V4.40002C0 5.00002 0.4 5.40002 1 5.40002H19C19.6 5.40002 20 5.00002 20 4.40002V1.40002C20 0.800024 19.6 0.400024 19 0.400024Z" fill="black"></path>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Ordered Date
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">
                                                        {{ \Carbon\Carbon::parse($order->order->created_at)->format('M d, Y : H:i')}}
                                                    </td>
                                                </tr>
                                                <!--end::Date-->
                                                <!--begin::Payment method-->
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->
                                                            <span class="svg-icon svg-icon-2 me-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z" fill="black"></path>
                                                                    <path opacity="0.3" d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z" fill="black"></path>
                                                                    <path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z" fill="black"></path>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Payment Method
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">{{ $order->order->payment_method }}

                                                </td></tr>
                                                <!--end::Payment method-->
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                            <span class="svg-icon svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z" fill="black"></path>
                                                                    <path d="M7 16H6C5.4 16 5 15.6 5 15V13H8V15C8 15.6 7.6 16 7 16Z" fill="black"></path>
                                                                    <path opacity="0.3" d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z" fill="black"></path>
                                                                    <path d="M18 16H17C16.4 16 16 15.6 16 15V13H19V15C19 15.6 18.6 16 18 16Z" fill="black"></path>
                                                                    <path opacity="0.3" d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z" fill="black"></path>
                                                                    <path d="M7 5H6C5.4 5 5 4.6 5 4V2H8V4C8 4.6 7.6 5 7 5Z" fill="black"></path>
                                                                    <path opacity="0.3" d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z" fill="black"></path>
                                                                    <path d="M18 5H17C16.4 5 16 4.6 16 4V2H19V4C19 4.6 18.6 5 18 5Z" fill="black"></path>
                                                                </svg></span>
                                                            <!--end::Svg Icon-->
                                                            Order Status
                                                        </div>

                                                    </td>
                                                    <td class="fw-bolder text-end">
                                                        @if ($order->order->status == 'Cancelled')
                                                            <span class="badge badge-light-danger"> Cancelled</span>
                                                        @elseif($order->order->status == 'Prescribed')
                                                            <span class="badge badge-light-success">
                                                                {{ $order->order->status }}</span>
                                                        @elseif($order->order->status == 'Declined')
                                                            <span class="badge badge-light-danger">
                                                                {{ $order->order->status }}</span>
                                                        @elseif($order->order->status == 'Pending')
                                                            <span class="badge badge-light-warning">
                                                                {{ $order->order->status }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
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
                                            <tbody>
                                                <!--begin::Customer ID-->
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                            <span class="svg-icon svg-icon-2 me-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                                                    <rect x="11" y="17" width="7" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                                                    <rect x="11" y="9" width="2" height="2" rx="1" transform="rotate(-90 11 9)" fill="currentColor"></rect>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Customer ID
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">
                                                        <a href="{{url('')}}/admin/{{$order->order->user->id}}/customers-view" class="text-gray-600 text-hover-primary"> {{$order->order->user->id}}</a>

                                                    </td>
                                                </tr>
                                                <!--end::Customer ID-->
                                                <!--begin::Liberty ID-->
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                            <span class="svg-icon svg-icon-2 me-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                                                    <rect x="11" y="17" width="7" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                                                    <rect x="11" y="9" width="2" height="2" rx="1" transform="rotate(-90 11 9)" fill="currentColor"></rect>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Liberty Patient ID
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">
                                                        @if ($order->order->user->liberty_patient_id)
                                                        <a href="{{ route('admin.customers.view', [$order->order->user->id]) }}"
                                                            class="text-gray-600 text-hover-primary"> {{ $order->order->user->liberty_patient_id}}</a>
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
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black"></path>
                                                                    <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black"></path>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Customer
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <a href="{{ route('admin.customers.view', [$order->order->user->id]) }}" class="text-gray-600 text-hover-primary">{{ $order->order->user->first_name }} {{ $order->order->user->last_name }}</a>
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
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="black"></path>
                                                                    <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="black"></path>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Email
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">
                                                        <a href="{{ route('admin.customers.view', [$order->order->user->id]) }}" class="text-gray-600 text-hover-primary">{{ $order->order->user->email }}</a>
                                                    </td>
                                                </tr>
                                                <!--end::Payment method-->
                                                <!--begin::Date-->
                                                <tr>
                                                    <td class="text-muted">
                                                        <div class="d-flex align-items-center">
                                                            <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->
                                                            <span class="svg-icon svg-icon-2 me-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path d="M5 20H19V21C19 21.6 18.6 22 18 22H6C5.4 22 5 21.6 5 21V20ZM19 3C19 2.4 18.6 2 18 2H6C5.4 2 5 2.4 5 3V4H19V3Z" fill="black"></path>
                                                                    <path opacity="0.3" d="M19 4H5V20H19V4Z" fill="black"></path>
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->Phone
                                                        </div>
                                                    </td>
                                                    <td class="fw-bolder text-end">{{ $order->order->user->phone }}</td>
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
                    </div>
                    <!--end::Orders-->
                    <div id="orderRefillLoader" class="card card-flush py-4 flex-row-fluid overflow-hidden mt-5">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Refill Transaction History</h2>
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
                                            <th>#</th>
                                            <th>Refill NO</th>
                                            <th>Transaction Status</th>
                                            <th>Transaction ID</th>
                                            <th>Transaction Message</th>
                                            <th>Total Amount</th>
                                            <th>Transaction Date</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>

                                        <!--begin::Orders-->
                                        @php $i=1; @endphp
                                        @foreach($order->refill_logs as $refill)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$refill->payment_for}}</td>
                                                <td>
                                                    @if($refill->status=='SUCCESS')
                                                        <span class="badge badge-light-success">Success</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Failed</span>
                                                    @endif
                                                </td>
                                                <td>{{$refill->transaction_id}}</td>
                                                <td>{{$refill->transaction_message}}</td>
                                                <td>${{$refill->amount}}</td>
                                                <td>{{ \Carbon\Carbon::parse($refill->created_at)->format('M d, Y : H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>


                        </div>

                    <!--end::Card body-->
                    </div>
                </div>
                <!--end::Tab pane-->
            </div>
            <!--end::Tab content-->
        </div>

    </div>
<!--end::Order details page-->
@endsection
@section('js')
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    @if (session()->has('warning'))
        <script>
            Swal.fire('{{ session()->get('warning') }}', '', 'warning')
        </script>
    @endif
@endsection

@section('footer')
    @include('admin.includes.footer')
@endsection
