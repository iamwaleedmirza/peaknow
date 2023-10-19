@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | Customers List')
@section('css')
		<!--begin::Page Vendor Stylesheets(used by this page)-->
	
		<!--end::Page Vendor Stylesheets-->
@endsection
@section('navbar')
@include('admin.includes.sidebar')
@endsection

@section('header')
@include('admin.includes.header')
@endsection

@section('content')
@if(session()->has('success'))
<script>
    Swal.fire('{{ session()->get('success') }}', '', 'success')
</script>
@endif
	<!--begin::Pending Order Listing-->
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
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
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
                    <input class="form-control form-control-solid rounded rounded-end-0" placeholder="Pick date range" id="kt_ecommerce_sales_flatpickr" />
                    <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr088.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="black" />
                                <rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="black" />
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
                        <th class="min-w-100px">#</th>
                        <th class="min-w-100px">Liberty PID</th>
                        <th class="min-w-175px">Name</th>
                        <th class="min-w-50px">Email</th>
                        <th class=" min-w-100px">Phone</th>
                        <th class=" min-w-100px">Joined Date</th>
                        <th class=" min-w-100px">Action</th>
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
    <!--end::Pending Order Listing-->
    @include('admin.pages.customers.modal.customer-reset-modal')
@endsection
@section('js')
    	<!--begin::Page Vendors Javascript(used by this page)-->
		<script src="{{asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
		<!--end::Page Vendors Javascript-->
		<!--begin::Page Custom Javascript(used by this page)-->
        <script>
            var docType = "Customers List";
            var customer_type = "{{@$type}}";
        </script>
		<script src="{{asset('admin_assets/assets/js/custom/apps/ecommerce/sales/customer-listing.js')}}"></script>
        <script src="{{ asset('admin_assets/assets/js/admin.ajax.js') }}"></script>
@endsection

@section('footer')
@include('admin.includes.footer')
@endsection