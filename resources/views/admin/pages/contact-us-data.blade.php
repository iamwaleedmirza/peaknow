@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | All Contact Us Subscribers')
@section('navbar')
    @include('admin.includes.sidebar')
@endsection

@section('header')
    @include('admin.includes.header')
@endsection


@section('content')
    <!-- Page-Title -->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" data-kt-ecommerce-order-filter="search"
                        class="form-control form-control-solid w-250px ps-14 search-textbox" placeholder="Search" />
                    <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_search_clear">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr088.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1"
                                    transform="rotate(-45 7.05025 15.5356)" fill="black" />
                                <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                                    transform="rotate(45 8.46447 7.05029)" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </button>
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <!--begin::Flatpickr-->
                <div class="input-group w-250px">
                    <input class="form-control form-control-solid rounded rounded-end-0" placeholder="Pick date range"
                        id="kt_ecommerce_sales_flatpickr" />
                    <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr088.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1"
                                    transform="rotate(-45 7.05025 15.5356)" fill="black" />
                                <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                                    transform="rotate(45 8.46447 7.05029)" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </button>
                </div>
                <!--end::Flatpickr-->
@include('admin.includes.export-btn')


            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0 server-table table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed gy-5" id="kt_datatable_example_1">
                <!--begin::Table head-->
                <thead>
                    <!--begin::Table row-->
                    <tr class="text-start  fw-bolder text-uppercase gs-0">
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>Subscribed Date</th>
                        <th>Action</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="">
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
      
    </div>

<div class="modal fade" tabindex="-1" id="view_contact">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Us Detail</h5>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                aria-label="Close">
                <!--begin::Svg Icon | path: assets/media/icons/duotune/abstract/abs012.svg-->
                <span class="svg-icon svg-icon-muted svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3"
                            d="M6.7 19.4L5.3 18C4.9 17.6 4.9 17 5.3 16.6L16.6 5.3C17 4.9 17.6 4.9 18 5.3L19.4 6.7C19.8 7.1 19.8 7.7 19.4 8.1L8.1 19.4C7.8 19.8 7.1 19.8 6.7 19.4Z"
                            fill="black" />
                        <path
                            d="M19.5 18L18.1 19.4C17.7 19.8 17.1 19.8 16.7 19.4L5.40001 8.1C5.00001 7.7 5.00001 7.1 5.40001 6.7L6.80001 5.3C7.20001 4.9 7.80001 4.9 8.20001 5.3L19.5 16.6C19.9 16.9 19.9 17.6 19.5 18Z"
                            fill="black" />
                    </svg></span>
                <!--end::Svg Icon-->

            </div>
                <!--end::Close-->
            </div>

            <div id="modal_loader" class="modal_loader mt-3"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="">
                        <input type="hidden" id="INO" value="">
                        <div class="row">
                            <div class="docs-id col-3">Name : </div>
                            <h3 class="col" id="name"></h3>
                        </div>
                    </div>
                </div>
                <div class="member-wrapper">
                    <h5>Personal Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="mem-info">
                                <h6>Subscribed On</h6>
                                <p id="date"></p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mem-info">
                                <h6>Phone Number</h6>
                                <p id="phones"></p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="mem-info">
                                <h6>State</h6>
                                <p id="state"></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="mem-info">
                                <h6>Email ID</h6>
                                <p id="email"></p>
                            </div>
                        </div>
                       
                        <hr>
                        <div class="col-sm-12">
                            <div class="mem-info">
                                <h6>Message</h6>
                                <p id="message"></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @if(in_array('admin.delete.contact',$permissions) || Auth::user()->u_type=='superadmin') {
            <div class="modal-footer">
                <button type="button" class="btn btn-danger deleteSubscriber" id="deleteSubscriber">Delete</button>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
@section('js')
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script>
        var docType = "Contactus Subscribers"
    </script>
    <script src="{{ asset('admin_assets/assets/js/custom/apps/ecommerce/sales/contact-us-listing.js') }}"></script>
    <script src="{{ asset('admin_assets/assets/js/admin.ajax.js') }}"></script>
@endsection
@section('footer')
    @include('admin.includes.footer')
@endsection
