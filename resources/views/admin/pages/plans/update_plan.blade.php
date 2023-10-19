@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Edit Plan')
@section('navbar')
    @include('admin.includes.sidebar')
@endsection

@section('header')
    @include('admin.includes.header')
@endsection

@section('content')
    <!-- Page-Title -->
  
    @if (session()->has('success'))
        <script>
            Swal.fire('{{ session()->get('success') }}', '', 'success')
        </script>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            @if (count($errors) > 0)
                                <!--begin::Alert-->
                                <div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                    <!--begin::Icon-->
                                    <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                                  fill="currentColor"/>
                                            <rect x="11" y="14" width="7" height="2" rx="1"
                                                  transform="rotate(-90 11 14)" fill="currentColor"/>
                                            <rect x="11" y="17" width="2" height="2" rx="1"
                                                  transform="rotate(-90 11 17)" fill="currentColor"/>
                                            </svg>
                                    </span>
                                    <!--end::Icon-->

                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                                        <!--begin::Title-->
                                        <h4 class="mb-2 text-light">Validation Error, Please correct them below.</h4>
                                        <!--end::Title-->

                                        <!--begin::Content-->
                                        @foreach ($errors->all() as $error)
                                            <span>{{ $error }}</span>
                                        @endforeach
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->

                                </div>
                                <!--end::Alert-->
                            @endif

                            <form id="edit-plan" action="{{ route('admin.update.plan') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="plan_id" value="{{ $plan->id }}"/>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan Image</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="plan_image" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-2">
                                        @if ($plan->plan_image)
                                            <img src="{{ getImage($plan->plan_image) }}" alt="favicon" height="50">
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan Title</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="plan_title"
                                               value="{{ !empty($plan) ? $plan->title : '' }}"
                                               placeholder="Enter plan title"
                                               class="form-control" maxlength="50">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan Subtitle</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Enter plan subtitle"
                                               value="{{ !empty($plan) ? $plan->sub_title : '' }}" class="form-control"
                                               maxlength="50" name="plan_subtitle">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan Pricing</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" placeholder="Enter plan price"
                                               name="plan_price" value="{{ !empty($plan) ? $plan->price : '' }}"
                                               maxlength="20" min="0" pattern="[0-9]*">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan Quantity</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" placeholder="Enter plan quantity"
                                               name="product_quantity"
                                               value="{{ !empty($plan) ? $plan->product_quantity : '' }}" step=".01">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan feature 1</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter feature 1"
                                               name="feature_1"
                                               value="{{ !empty($plan) ? $plan->feature_1 : '' }}">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan feature 2</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter feature 2"
                                               name="feature_2"
                                               value="{{ !empty($plan) ? $plan->feature_2 : '' }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan feature 3</label>
                                    </div>
                                    <div class="col-md-10 align-self-center">
                                        <input type="text" class="form-control" placeholder="Enter feature 3"
                                               name="feature_3"
                                               value="{{ !empty($plan) ? $plan->feature_3 : '' }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan feature 4</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter feature 4"
                                               name="feature_4"
                                               value="{{ !empty($plan) ? $plan->feature_4 : '' }}">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan NDC</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter plan NDC"
                                               name="product_ndc"
                                               value="{{ !empty($plan) ? $plan->product_ndc : '' }}" maxlength="20"
                                               min="0"
                                               pattern="[0-9]*">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan NDC 2</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter plan NDC 2"
                                               name="product_ndc_2"
                                               value="{{ !empty($plan) ? $plan->product_ndc_2 : '' }}" maxlength="20"
                                               min="0"
                                               pattern="[0-9]*">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Half Dose Verbiage</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" placeholder="Enter half dose verbiage"
                                               name="half_dose"
                                               value="{{ !empty($plan) ? $plan->half_dose : '' }}" >
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for=""></label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_popular" class="form-check-input"
                                                   id="isPopularCheck" @if ($plan->is_popular) checked @endif>
                                            <label class="form-check-label" for="isPopularCheck">Is Popular</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2">
                                        <label class="p-t-10" for=""></label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_subscription_based" class="form-check-input"
                                                   id="isSubscriptionBasedCheck"
                                                   @if ($plan->is_subscription_based) checked @endif>
                                            <label class="form-check-label" for="isSubscriptionBasedCheck">
                                                Is Subscription based plan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-end m-3">
                                    <button id="edit-plan-btn" class="btn btn-primary btn-hover-scale me-5"
                                            data-style="slide-right"
                                            type="submit">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    @include('admin.includes.footer')
@endsection
@section('js')
    <script>
        // Define form element
        const form = document.getElementById('edit-plan');

        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'plan_title': {
                        validators: {
                            notEmpty: {
                                message: 'Plan title is required'
                            }
                        }
                    },
                    'plan_subtitle': {
                        validators: {
                            notEmpty: {
                                message: 'Plan subtitle is required'
                            }
                        }
                    },
                    'plan_price': {
                        validators: {
                            notEmpty: {
                                message: 'Plan price is required'
                            }
                        }
                    },
                    'product_quantity': {
                        validators: {
                            notEmpty: {
                                message: 'Plan quantity is required'
                            }
                        }
                    },
                    'feature_1': {
                        validators: {
                            notEmpty: {
                                message: 'Plan feature 1 is required'
                            }
                        }
                    },
                    'feature_2': {
                        validators: {
                            notEmpty: {
                                message: 'Plan feature 2 is required'
                            }
                        }
                    },
                    'product_ndc': {
                        validators: {
                            notEmpty: {
                                message: 'Plan NDC number is required'
                            }
                        }
                    },
                    'product_ndc_2': {
                        validators: {
                            notEmpty: {
                                message: 'Plan NDC2 number is required'
                            }
                        }
                    },
                    'half_dose': {
                        validators: {
                            notEmpty: {
                                message: 'Half dose verbiage is required'
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
        const submitButton = document.getElementById('edit-plan-btn');
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
    @if (session()->has('success'))
        <script>
            Swal.fire('{{ session()->get('success') }}', '', 'success')
        </script>
    @endif
    @if (session()->has('warning'))
        <script>
            Swal.fire('{{ session()->get('warning') }}', '', 'warning')
        </script>
    @endif
@endsection
