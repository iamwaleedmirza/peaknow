@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Order View')
@section('css')
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <style>
        .step-icon > i{
            font-size: 38px !important;
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

@section('content')
@php
$currentPlace = explode('/',URL::previous());


if (isset($currentPlace[5]) && $currentPlace[5] == 'customers-view') {
    $currentPlace[4] = 'customers-list';
}

$urlName = explode('?',$currentPlace[4]);
echo '<style>
    .aside-menu .menu .menu-item .menu-link.'.$urlName[0].' {
        transition: color 0.2s ease, background-color 0.2s ease;
        background-color: #3699FF;
        color: #ffffff;
    }
    .'.$currentPlace[4].' > .menu-title{
        color: #ffffff !important;
    }
    .breadcrumb-line .breadcrumb-item:after {
       content: ">" !important;
    }
</style>
';
$currentPlace = str_replace('-',' ',explode('/',URL::previous()));
if (isset($currentPlace[5]) && $currentPlace[5] == 'customers view') {
    $currentPlace[4] = 'customers view';
}
$urlName = explode('?',$currentPlace[4]);
@endphp

<ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-bold mb-4">
<li class="breadcrumb-item pe-3"><a href="{{route('admin.home')}}" class="pe-3">Home</a></li>
<li class="breadcrumb-item pe-3"><a href="{{URL::previous()}}" class="pe-3">{{ucwords($urlName[0])}}</a></li>
<li class="breadcrumb-item px-3 text-muted">Order Detail</li>
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
                                href="#kt_ecommerce_sales_order_summary">Refill Summary</a>
                        </li>
                        <!--end:::Tab item-->

                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Button-->



                </div>

                <!--begin::Order summary-->
                <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
                    <!--begin::Order details-->
                    <div class="card card-flush  flex-row-fluid">
                        <!--begin::Card header-->



                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody class="">
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">

                                            </td>
                                        </tr>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/files/fil002.svg-->

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">

                                            </td>
                                        </tr>
                                        <!--end::Date-->
                                        <!--begin::Payment method-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">


                                            </td>
                                        </tr>
                                        <!--end::Payment method-->
                                        <!--begin::Payment method-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin008.svg-->

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">

                                        </tr>
                                        <!--end::Payment method-->
                                        <tr>
                                            <td class="">
                                                <div class="d-flex align-items-center text-muted">
                                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->

                                                    <!--end::Svg Icon-->

                                                </div>

                                            </td>
                                            <td class="fw-bolder text-end">

                                            </td>
                                        </tr>

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
                                <h2></h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                                    <!--begin::Table body-->
                                    <tbody class="">
                                        <!--begin::Customer name-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <div class="d-flex align-items-center justify-content-end">

                                                    <!--begin::Name-->
                                                    <a href="{{route('admin.customers.view',[$order->user->id])}}"
                                                        class="text-gray-600 text-hover-primary"></a>
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

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end">
                                                <a href="{{route('admin.customers.view',[$order->user->id])}}"
                                                    class="text-gray-600 text-hover-primary"></a>
                                            </td>
                                        </tr>
                                        <!--end::Payment method-->
                                        <!--begin::Date-->
                                        <tr>
                                            <td class="text-muted">
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/electronics/elc003.svg-->

                                                    <!--end::Svg Icon-->
                                                </div>
                                            </td>
                                            <td class="fw-bolder text-end"></td>
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
                <!--begin::Tab content-->
                <div class="tab-content">

                    <!--begin::Tab pane-->
                    <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                        <!--begin::Orders-->
                        <div class="d-flex flex-column gap-7 gap-lg-10">
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
                                            <h2></h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0" >
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
                                                <h2></h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->

                                        <div class="card-body pt-0">

                                        </div>
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
                                                <h2></h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        @php

                                        @endphp
                                        <div class="card-body pt-0">
                                            <div class="d-flex align-items-center text-bold">
                                                <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                <span class="svg-icon svg-icon-2 me-2"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z"
                                                            fill="black" />
                                                        <path opacity="0.3"
                                                            d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z"
                                                            fill="black" />
                                                    </svg></span>
                                                <!--end::Svg Icon-->



                                            </div>
                                            <br>
                                            <div class="d-flex align-items-center text-bold">
                                                <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen046.svg-->
                                                <span class="svg-icon svg-icon-2 me-2"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z"
                                                            fill="black" />
                                                        <path opacity="0.3"
                                                            d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z"
                                                            fill="black" />
                                                    </svg></span>
                                                <!--end::Svg Icon-->


                                            </div>

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
                                        <!--end::Background-->
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2></h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">
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
                                        <div class="card-header" style="min-height: 24px;">
                                            <div class="card-title">
                                                <h5></h5>
                                            </div>
                                        </div>
                                        <!--end::Card header-->

                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2></h2>
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                        <!--begin::Card body-->
                                        <div class="card-body pt-0">

                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                @endif

                                <!--end::Response-->
                            </div>


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

        </div>
    <!--end::Order details page-->

@include('admin.pages.orders.modals.refund-cancelled-modal')
@include('admin.pages.orders.modals.refund-declined-modal')


@endsection
@section('js')
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->

    <script src="{{ asset('admin_assets/assets/js/admin.ajax.js') }}"></script>
    <script type="text/javascript">
        function refundOrder(order_id) {
            $("#order_id").val(order_id);
            $("#orderId").html(order_id);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                ajaxPostData('{{route('admin.order.refill.data',[$order,$refill_number])}}', '', 'GET', '#order_details', 'orderView', blockUIOrderView)
            }, 0);
        });
        $(document).on('click', "#view-refill", function() {
            let uri = $(this).attr('data-uri');
            let changeUri = $(this).attr('data-change-uri');
            history.pushState(null, null, changeUri);
            window.scrollTo({ top: 0, behavior: 'smooth' });
            ajaxPostData(uri, '', 'GET', '#order_details', 'orderView', blockUIOrderView)

        });
        $(document).on('click', "#gotoOrderDetails", function() {
            history.pushState(null, null, '{{route('admin.view.orders',[$order])}}');
            ajaxPostData('{{route('admin.orders.data',[$order])}}', '', 'GET', '#order_details', 'orderView', blockUIOrderView)

        });
    </script>
    <script>
        @php
        $currentPlace = explode('/',URL::previous());
        $urlName = explode('?',$currentPlace[4]);
        @endphp
        $('.{{$urlName[0]}}').parent().parent().parent().addClass("show here")

    </script>
    @if (session()->has('success'))
        <script>

        </script>
    @endif
    @if (session()->has('warning'))
        <script>
            Swal.fire('{{ session()->get('warning') }}', '', 'warning')
        </script>
    @endif
@endsection

@section('footer')
    @include('admin.includes.footer')
@endsection
