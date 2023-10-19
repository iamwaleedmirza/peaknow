@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Promo Codes')
@section('css')
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
        .blockui-overlay {
            transition: all 0.3s ease;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .blockui-message {
            display: flex;
            align-items: center;
            border-radius: 0.95rem;
            box-shadow: 0 0 50px 0 rgb(82 63 105 / 15%);
            background-color: #fff;
            color: #7e8299;
            font-weight: 500;
            margin: 0 !important;
            width: auto;
            padding: 0.85rem 1.75rem !important;
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
    <!--begin::Promo Code Listing-->
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
                <!--begin::Add PromoCode-->
                @if(in_array('admin.customers.change.password',$permissions) || Auth::user()->u_type=='superadmin')
                <button type="button" class="btn btn-primary add-promo" >Add New Promo Code</button>
                <!--end::Add PromoCode-->
                @endif

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
                        <th >#</th>
                        <th >Promo Code</th>
                        <th >Promo Code Type</th>
                        <th >Plan Details</th>
                        <th>Discount (%)</th>
                        <th >Status</th>
                        <th >Created Date</th>
                        <th >Updated Date</th>
                        <th >Action</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Promo Code Listing-->
    @include('admin.pages.promocode.modal.add')
@endsection
@section('js')
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Page Vendors Javascript-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script>
        var docType = "Promo Codes"
    </script>
    <script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin_assets/assets/js/custom/apps/ecommerce/sales/promocode-listing.js') }}"></script>
    <!-- <script src="{{ asset('admin_assets/assets/js/admin.ajax.js') }}"></script> -->
    <script>

        $(".select-plan-data,.select-product-data,.select-plans-data,.select-medicine-data").hide();

        function IsNumeric(e) {
            var key = e.keyCode;
            var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9.]/);
                    if (verified) {e.preventDefault();}
        }

        var targetOrderView = document.querySelector("#kt_body");
        var blockUIOrderView = new KTBlockUI(targetOrderView, {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Processing...</div>',
        });

        $("input[name='promo_name']").on('input', function(evt) {
        $(this).val(function(_, val) {
            return val.toUpperCase();
        });
        $(this).val(function(_, val) {
            return val.replace(/[^A-Z0-9]/g,'');
        });
        });

        $("input[name='promo_type']").change(function(){
            let type = $(this).val();
            $('.promeValue').show();
            $(".select-plan-data,.select-product-data,.select-plans-data,.select-medicine-data").hide();
            if (type == 1) {
                $(".select-plan-data").show();
            } else if (type == 2) {
                $(".select-product-data").show();
            } else if (type == 3) {
                $(".select-plans-data").show();
            } else if (type == 4) {
                $(".select-medicine-data").show();
            }
        })
    </script>
@endsection

@section('footer')
    @include('admin.includes.footer')
@endsection
