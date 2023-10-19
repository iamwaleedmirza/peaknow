@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | Shipping Setting')
@section('navbar')
@include('admin.includes.sidebar')
@endsection

@section('header')
@include('admin.includes.header')
@endsection


@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            @if(count($errors) > 0 )

                                <!--begin::Alert-->
                                    <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                                        <!--begin::Icon-->
                                        <!--begin::Svg Icon | path: icons/duotune/communication/com003.svg-->
                                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black" />
                                                <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <!--end::Icon-->
                                        <!--begin::Content-->
                                        <div class="d-flex flex-column pe-0 pe-sm-10">
                                            <h5 class="mb-1">Error</h5>
                                            @foreach($errors->all() as $error)
                                                <span>{{ $error }}</span>
                                            @endforeach
                                        </div>
                                        <!--end::Content-->
                                        <!--begin::Close-->
                                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                            <i class="bi bi-x fs-1 text-danger"></i>
                                        </button>
                                        <!--end::Close-->
                                    </div>
                                    <!--end::Alert-->
                            @endif

                            <form id="form-beluga-setting" method="post" >
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-md-12 align-self-center">
                                        <label class="p-t-10 form-label" for="">Beluga Consultation Fee</label>
                                        <input type="number" name="consultation_fee"
                                               value="{{($setting)?$setting->consultation_fee:''}}"
                                               placeholder="Enter Consultation Fee" class="form-control" step=".01">
                                    </div>
                                </div>
                                @if(in_array('admin.update.consultation.fee',$permissions) || Auth::user()->u_type=='superadmin') 
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary btn-hover-scale me-5" data-style="slide-right"
                                            type="submit">
                                        Update
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/additional-methods.min.js') }}"></script>
<script type="text/javascript">
    var targetOrderView = document.querySelector("#kt_body");
    var blockUIOrderView = new KTBlockUI(targetOrderView, {
        message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Generating Refill...</div>',
    });
    $("#form-beluga-setting").validate({
        rules: {
            consultation_fee: {
                required: true
            },
        },
        messages: {
            consultation_fee: {
                required: "Consultation fee is required"
            }
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form, event) { // for demo
            event.preventDefault();
            var formData = new FormData(form);
            // blockUIOrderView.block();
            $.ajax({
                type: "POST",
                url: "/admin/consultation-fees",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.status){
                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            showConfirmButton: true,
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
                error: function ()  {
                    Swal.fire({
                        icon: 'error',
                        text: 'Something went wrong',
                        showConfirmButton: true,
                    });
                    blockUIOrderView.release();
                }
            });
        }
    });
</script>
@endsection
@section('footer')
@include('admin.includes.footer')
@endsection
