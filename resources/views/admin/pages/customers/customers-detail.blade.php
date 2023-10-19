@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Customer View')

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
    {{--@php

    $currentPlace = explode('/', URL::previous());
    if (isset($currentPlace[5]) && $currentPlace[5] !== 'customers-view') {
        $currentPlace[4] = 'customers-list';
    } 
    if (isset($currentPlace[4])) {
    $urlName = explode('?', $currentPlace[4]);
    echo '<style>
            .aside-menu .menu .menu-item .menu-link.' .
        $urlName[0] .
        ' {
                transition: color 0.2s ease, background-color 0.2s ease;
                background-color: #3699FF;
                color: #ffffff;
            }
            .' .
        $currentPlace[4] .
        ' > .menu-title{
                color: #ffffff !important;
            }
            .breadcrumb-line .breadcrumb-item:after {
               content: ">" !important;
            }
        </style>
        ';
    }else{
        $currentPlace[4] = 'customer';
    }
    $currentPlace = str_replace('-', ' ', explode('/', URL::previous()));
    if (isset($currentPlace[5]) && $currentPlace[5] !== 'customers view') {
        $currentPlace[4] = 'customers list';
        $url = route('admin.customers.list');
    } else {
        $url = URL::previous();
    }
    $urlName = explode('?', $currentPlace[4]);
    
    if (is_numeric($urlName[0])) {
        $urlName[0] = 'customer';
    }
    
    @endphp--}}
    <div class="row">
        <div class="col-lg-6">
            <ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-bold mb-4">
                <li class="breadcrumb-item pe-3"><a href="{{ route('admin.home') }}" class="pe-3">Home</a></li>
                <li class="breadcrumb-item pe-3"><a href="{{route('admin.customers.list')}}" class="pe-3">Customers List</a>
                </li>
                <li class="breadcrumb-item px-3 text-muted">Customer Detail</li>

                
            </ol>
        </div>
        <div class="col-lg-6 next-previous mb-2">
            <div class="float-right">
                @if($previous)
                    <a href="{{route('admin.customers.view', [$previous->id])}}" class="breadcrumb-item px-3 fs-4"><i class="fa fa-angle-left" aria-hidden="true"></i> Previous Customer </a>
                @else
                    <a href="javascript:void(0)" class="breadcrumb-item px-3 fs-4 text-muted pointer-none"><i class="fa fa-angle-left text-muted pointer-none" aria-hidden="true"></i> Previous Customer </a>
                @endif
                |
                @if($next)
                    <a href="{{route('admin.customers.view', [$next->id])}}" class="breadcrumb-item px-3 fs-4">Next Customer <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                @else
                    <a href="javascript:void(0)" class="breadcrumb-item px-3 fs-4 text-muted pointer-none">Next Customer <i class="fa fa-angle-right text-muted pointer-none" aria-hidden="true"></i></a>
                @endif
            </div>
        </div>
    </div>
    <!--begin::Order details page-->
    <div id="customer_details">
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <!--begin::Card-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-15">
                        <!--begin::Summary-->
                        <div class="d-flex flex-center flex-column mb-5">
                            <!--begin::Avatar-->

                            <!--end::Avatar-->
                            <!--begin::Name-->
                            <p
                                class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">Customer Detail</p>
                            <!--end::Name-->
                            <!--begin::Position-->
                            <div class="fs-5 fw-bold text-muted mb-6"></div>
                            <!--end::Position-->

                        </div>
                        <!--end::Summary-->
                        <!--begin::Details toggle-->
                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details"
                                role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Details
                                <span class="ms-2 rotate-180">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path
                                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator separator-dashed my-3"></div>
                        <!--begin::Details content-->
                        <div id="kt_customer_view_details" class="collapse show">
                            <div class="py-5 fs-6">
                                <!--begin::Badge-->
                                {{-- <div class="badge badge-light-info d-inline">Premium user</div> --}}
                                <!--begin::Badge-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5"></div>
                                <div class="text-gray-600"></div>
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5"></div>
                                <div class="text-gray-600">

                                </div>
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5"></div>
                                <div class="text-gray-600">
                                    <a href="#" class="text-gray-600 text-hover-primary"></a>
                                </div>
                                <div class="fw-bolder mt-5"></div>
                                <div class="text-gray-600">
                                    <a href="#" class="text-gray-600 text-hover-primary">


                                    </a>
                                </div>
                                <div class="fw-bolder mt-5"></div>
                                <div class="text-gray-600">
                                    <a href="#" class="text-gray-600 text-hover-primary"></a>
                                </div>
                                <div class="fw-bolder mt-5"></div>
                                <div class="text-gray-600">
                                    <a href="#" class="text-gray-600 text-hover-primary"></a>
                                </div>
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5"></div>

                                <div class="text-gray-600"><br>

                                </div>

                            </div>
                        </div>
                        <!--end::Details content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

            </div>
            <!--end::Sidebar-->
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_customer_view_overview_tab">Overview</a>
                    </li>
                    <!--end:::Tab item-->


                </ul>
                <!--end:::Tabs-->
                <!--begin:::Tab content-->
                <div class="tab-content" id="myTabContent">
                    <!--begin:::Tab pane-->
                    <div class="tab-pane fade active show" id="kt_customer_view_overview_tab" role="tabpanel">
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bolder">Total Spending</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">

                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->

                            <div class="card-body pt-0">
                                <div class="fw-bolder fs-2">$0
{{--                                    <span class="text-muted fs-4 fw-bold">USD</span>--}}
                                    <div class="fs-7 fw-normal text-muted"></div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Order Records</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Filter-->

                                    <!--end::Filter-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                                    <!--begin::Table head-->
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                        <!--begin::Table row-->
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-100px">Order No</th>
                                            <th>Order Status</th>
                                            <th>Order Amount</th>
                                            <th class="min-w-100px">Ordered Date</th>
                                            <th class="text-end min-w-100px pe-4">Actions</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fs-6 ">

                                            <tr>
                                                <td colspan="5" class="text-center">No Order Data</td>
                                            </tr>

                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>

                        <!--end::Card-->

                    </div>

                </div>
                <!--end:::Tab content-->
            </div>
            <!--end::Content-->
        </div>



    </div>
    <!--end::Order details page-->

@include('admin.pages.customers.modal.customer-reset-modal')
@endsection
@section('js')
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

    <!--end::Page Vendors Javascript-->

    <script src="{{ asset('admin_assets/assets/js/admin.ajax.js') }}"></script>

    <script type="text/javascript">

        $(document.body).on('click', '.customer_account_status_update', function () {
            let id = $(this).attr('data-user-id');
            let type = $(this).attr('data-user-status');
            var message = (type==0) ? 'Are you sure you want to active this user' : 'Are you sure you want to disable this user';
            Swal.fire({
                showLoaderOnConfirm: true,
                title: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Yes",
                closeOnConfirm: false,
                closeOnClickOutside: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
            }).then((result) => {
            if (result.value==true) {
                blockUIOrderView.block();
                    $.ajaxSetup({
                      headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                    });
                    $.ajax({
                        type: "post",
                        url: "/admin/update-user-account-status",
                        data:{id:id,status:type},
                        dataType: "json",
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    text: response.message,
                                    showConfirmButton: true,
                                }).then(function() {
                                  location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    text: response.message,
                                    showConfirmButton: true,
                                });
                            }
                            blockUIOrderView.release();
                        },
                        error: function () 
                        {
                            Swal.fire({
                                icon: 'error',
                                text: 'Something went wrong',
                                showConfirmButton: true,
                            });
                            blockUIOrderView.release();
                        }
                    });
                }
          })
        });

        $(document).ready(function() {
            setTimeout(function() {
                ajaxPostData('{{ route('admin.customers.data', [$customer]) }}', '', 'GET',
                    '#customer_details', 'customerView', blockUIOrderView)

            }, 0);

            $(document).on('click', '.customer-action', function() {

            });

            $('#order_amount_type').on('change', function() {
                if (this.value == "total_amount") {
                    $('.order_amount_data').html(
                        '<input type="text" name="order_amount" value="100%" hidden/>');
                } else if (this.value == "partial_amount") {
                    $('.order_amount_data').html(
                        '<input type="text" name="order_amount" value="50%" hidden/>');
                } else if (this.value == "custom_amount") {
                    var order_amount = $('.order_refund_modal_btn').attr('data-order_amount');
                    $('.order_amount_data').html(
                        '<br><label>Enter Custom Amount *</label><input type="number" class="form-control" name="order_amount" value="" min="1" max="' +
                        order_amount + '" step="0.01" />'
                    );
                } else {
                    $('.order_amount_data').html('');
                }
            });

        });
    </script>
    <script>
        @php
        $currentPlace = explode('/', URL::previous());
        if (isset($currentPlace[4])) {
            $urlName = explode('?',$currentPlace[4]);
        } else {
            $urlName[0]='customer-list';
        }
    
        if (is_numeric($urlName[0])) {
            $urlName[0] = 'customer-list';
        }
        @endphp
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
