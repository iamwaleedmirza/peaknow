@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | Permission')
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
                    <input type="text" data-kt-ecommerce-order-filter="search" id="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search Permissions" />
                </div>
                <!--end::Search-->
            </div>
            <!-- <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
               <a href="javascript:void(0)" type="button" class="btn btn-secondary pointer-none" id="permission">
                Add New Permission
                </a>
            </div> -->
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
                        <th class="min-w-175px">Title</th>
                        <th class="min-w-175px">Permission (Route Name)</th>
                        <th class="min-w-175px">Module Name</th>
                        <th class="min-w-175px">Permission For</th>
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
    @include('admin.pages.permission.modal.add-edit-permission')
@endsection
@section('js')
	<!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/additional-methods.min.js') }}"></script>
	<!-- <script src="{{asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script> -->
    <script src="{{ asset('admin_assets/js/permission.js') }}"></script>
@endsection

@section('footer')
@include('admin.includes.footer')
@endsection