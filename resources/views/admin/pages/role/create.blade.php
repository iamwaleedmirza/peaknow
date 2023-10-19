@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | Add New Role')
@section('css')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
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
    <input type="hidden" id="base_url" value="{{url('/')}}">
	<!--begin::Pending Order Listing-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            @if(@$role->name)
                <h2>Edit User Role</h2>
            @else
                <h2>Add User Role</h2>
            @endif
            
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
            <div class="row">
                <form id="data_form" class="modal_body" method="post">
                    <input type="hidden" name="id" value="{{@$role->id}}">
                @csrf
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label">User Role Name <span class="error-sign">*</span></label>
                            <input type="text" name="name" value="{{@$role->name}}" placeholder="Enter User Role Name" class="form-control form-control-solid" id="name" value="">
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        
                        <div class="row px-3">
                            @foreach($array as $per_data)
                                <span class="form-label fw-bold fs-3 mb-1 mt-1 d-flex justify-content-center permission-label">{{$per_data['name']}}</span>
                                <div class="col-md-10 form-check form-check-custom role-check mb-3 mt-3 mx-2 select-all-check form-check-sm">
                                    <input type="checkbox"  name="checkall" value="" class='form-check-input checkall =' checkbox-id="{{$per_data['id']}}" id="checkall-{{$per_data['id']}}"> <label class="form-check-label fs-7 " for="checkall-{{$per_data['id']}}">  All Permissions</label>
                                </div>
                                @foreach($per_data['module'] as $mod)
                                <div class="separator my-2"></div>
                                    <div class="col-md-12 form-check form-check-custom role-check mb-3 mt-3 select-all-check"> 
                                        <label class="form-check-label fw-bold" for="flexCheckDefault">  {{($mod->module_name) ? $mod->module_name : 'Default'}}</label>
                                    </div>
                                    @foreach($mod['module_sub_data'] as $permis)
                                    <div class="col-md-12 form-check form-check-custom role-check mb-2 form-check-sm">
                                        <input type="checkbox" checkbox-input-id="{{$per_data['id']}}" name="permission[]" value="{{ $permis->name }}" id="flexCheckDefault{{$permis->id}}" class='form-check-input form-check-label checkboxes checkboxes{{$per_data['id']}} ' {{ (in_array($permis->name, $permissions)) ? 'checked' : '' }}> <label class="form-check-label fs-7" for="flexCheckDefault{{$permis->id}}"> {{$permis->title}}</label>
                                    </div>
                                    @endforeach                                
                                @endforeach
                                <div class="separator border-dark my-3"></div>
                        @endforeach
                    </div>
                        <label id="checkbox-error" class="error"></label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button class="ladda-button btn btn-primary" data-style="slide-right" type="submit" id="Modal-btn">Submit <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Pending Order Listing-->
@endsection
@section('js')
	<!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/additional-methods.min.js') }}"></script>
	<script src="{{asset('admin_assets/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('admin_assets/js/add-edit-role.js') }}"></script>
@endsection

@section('footer')
@include('admin.includes.footer')
@endsection