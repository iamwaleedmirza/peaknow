@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | General Setting')
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

                            <form id="form-general-settings" action="{{route('general-setting.post')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Site title</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="site_title"
                                               value="{{($setting)?$setting->site_title:''}}"
                                               placeholder="Enter site title" class="form-control">
                                    </div>
                                </div>
                                {{-- <div class="row mt-3">
                                    <div class="col-md-2 align-self-center"><label class="p-t-10" for="">Site logo (Light)</label></div>
                                    <div class="col-md-4">
                                        <input type="file" name="site_logo_light" class="form-control" accept="image/*">
                                        @if($setting && $setting->site_logo_light)
                                        <img src="{{getImage($setting->site_logo_light)}}"
                                         alt="logo" height="50">
                                         @endif
                                    </div>
                                    <div class="col-md-2 align-self-center"><label class="p-t-10" for="">Site logo (Dark)</label></div>
                                    <div class="col-md-4">
                                        <input type="file" name="site_logo_dark" class="form-control" accept="image/*">
                                        @if($setting && $setting->site_logo_dark) 
                                        <img src="{{getImage($setting->site_logo_dark)}}"
                                             alt="logo" height="50">
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Site favicon</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="site_favicon" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-2 align-self-center">
                                        @if($setting  && $setting->site_favicon)
                                        <img src="{{getImage($setting->site_favicon)}}"
                                             alt="favicon" height="50">
                                         @endif
                                    </div>
                                </div> --}}
                                {{-- <div class="row mt-3">
                                    <div class="col-md-2 align-self-center"><label class="p-t-10" for="">Footer text</label></div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Enter footer area text"
                                               value="{{($setting)?$setting->footer_text:''}}" class="form-control"
                                               name="footer_text">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center"><label class="p-t-10" for="">Facebook Link</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter facebook link"
                                               name="facebook_link" value="{{($setting)?$setting->facebook_link:''}}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center"><label class="p-t-10" for="">Twitter Link</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter twitter link"
                                               name="twitter_link" value="{{($setting)?$setting->twitter_link:''}}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Instagram Link</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter instagram link"
                                               name="instagram_link" value="{{($setting)?$setting->instagram_link:''}}">
                                    </div>
                                </div>--}}
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Support Mail:</label></div>
                                    <div class="col-md-10">
                                        <input type="email" class="form-control" placeholder="Enter support mail"
                                               name="support_mail" value="{{($setting)?$setting->support_mail:''}}">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">States where site will be live:</label></div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Add states (separated by comma)"
                                               name="allowed_states" value="{{($setting) ? $setting->allowed_states : ''}}">
                                    </div>
                                </div>

                                @if(in_array('pages-content.post',$permissions) || Auth::user()->u_type=='superadmin') 
                                    <div class="form-group text-end m-3">
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
<script>
    // Define form element
    const form = document.getElementById('form-general-settings');

    var validator = FormValidation.formValidation(
        form,
        {
            fields: {
            'site_title': {
                    validators: {
                        notEmpty: {
                            message: 'Site title input is required'
                        }
                    }
                },
            'support_mail': {
                    validators: {
                        notEmpty: {
                            message: 'Support mail input is required'
                        }
                    }
                },  
            'allowed_states': {
                    validators: {
                        notEmpty: {
                            message: 'Allowed states input is required'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: ''
                })
            }
        }
    );

    // Submit button handler
    const submitButton = document.getElementById('update-settings-btn');
    submitButton.addEventListener('click', function (e) {
        // Prevent default button action
        e.preventDefault();

        // Validate form before submit
        if (validator) {
            validator.validate().then(function (status) {
                console.log('validated!');

                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    form.submit();
                }
            });
        }
    });
</script>
@if(session()->has('success'))
<script>
    Swal.fire('{{ session()->get('success') }}', '', 'success')
</script>
@endif
@endsection
@section('footer')
@include('admin.includes.footer')
@endsection
