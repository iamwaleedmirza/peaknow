            <!--begin::Form-->
            <form class="form" method="POST"
                action="{{ route('admin.post.promo.code-update-form', [$promoCode->id]) }}"
                id="kt_modal_update_promocode_form">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_promocode_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder">Update Promo Code</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_update_promocode_close"
                        class="btn btn-icon btn-sm btn-active-icon-primary kt_modal_update_promocode_close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_update_promocode_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_update_promocode_header"
                        data-kt-scroll-wrappers="#kt_modal_update_promocode_scroll" data-kt-scroll-offset="300px">
                        <div class="row">
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold mb-2">Promo Title</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter promo name" name="edit_promo_name"
                                        value="{{ $promoCode->promo_name }}" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold mb-2">Promo Plan</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select form-select-solid select-update-plan"
                                        data-control="select2" data-placeholder="Choose Plans" data-allow-clear="true"
                                        name="edit_plan_id[]" multiple>
                                        @foreach ($plans as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex fv-row flex-column gap-2 mb-7">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-bold mb-2">Discount Type</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div>
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" type="radio" checked name="edit_promo_type"
                                        id="promo_type1" value="percent">
                                    <label class="form-check-label" for="promo_type1">Discount by Percentage</label>
                                </div>

                                {{-- <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="promo_type" id="promo_type2" value="amount">
                                    <label class="form-check-label" for="promo_type2">Discount by Amount</label>
                                  </div> --}}
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-2 ">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-bold mb-2">Discount Percentage</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" id="promo_value_by_precent" class="form-control form-control-solid promo_value_by_precent"
                                placeholder="Enter Discount Percentage" name="edit_promo_value"
                                value="{{ $promoCode->promo_value }}%" />
                            {{-- <input type="number" id="promo_value_by_amount"  style="display: none;" class="form-control form-control-solid" placeholder="Enter promo value"
                            value="" /> --}}
                            <!--end::Input-->
                        </div>
                        <div class="fv-plugins-message-container text-primary mb-2"><div data-field="promo_name" data-validator="notEmpty">Note: Discount percentage should be between 1% to 99%</div></div>
                        <div class="fv-row mb-7 ">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-bold mb-2">Promo Status</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select" aria-label="Select Promo Status" name="edit_promo_status">

                                <option value="1" @if ($promoCode->promo_status == true) selected  @endif >Active</option>
                                <option value="0" @if ($promoCode->promo_status == false) selected  @endif >InActive</option>
                            </select>
                            {{-- <input type="number" id="promo_value_by_amount"  style="display: none;" class="form-control form-control-solid" placeholder="Enter promo value"
                            value="" /> --}}
                            <!--end::Input-->
                        </div>
                        
                        <!--end::Input group-->
                        <div class="fv-row mb-7 updatePromoAfterCal">

                        </div>
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_update_promocode_submit" class="btn btn-primary kt_modal_promocode_submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
            <script>
                KTApp.init();
                $('.select-update-plan').select2().val([{{ $promoCode->plan_id }}]).trigger("change")
                $('.kt_modal_update_promocode_close').on('click', function() {
                    $('#kt_modal_update_promocode').modal('hide');
                })
                $("input[name='edit_promo_name']").on('input', function(evt) {
                    $(this).val(function(_, val) {
                        return val.toUpperCase();
                    });
                    $(this).val(function(_, val) {
                        return val.replace(/[^A-Z0-9]/g,'');
                    });
                });
                $("input[name='edit_promo_value']").on('input', function(evt) {
                    let data = $(this);
                    let val = data.val();
                    let type = $("input[name='edit_promo_type']:checked").val();
                    if (val.replace('%','') == null || val.replace('%','') == "" || val.replace('%','') == " ") {
                       setTimeout(() => {
                        $('.updatePromoAfterCal').html('');
                       }, 500);
                        return false;
                    }
                    if (type == 'percent') {
                        if (val.replace('%','') < 1 || val.replace('%','') > 99 || val == null || val == "")  {
                            // $('.promo-val-err').remove();
                            data.val('');
                            $('.updatePromoAfterCal').html('');
                            // $('<div class="fv-plugins-message-container invalid-feedback promo-val-err"><div data-field="promo_name" data-validator="notEmpty">Promo value must between 1% and 99%</div></div>')
                            //     .insertAfter(data);
                            return false;
                        } else {
                            $(this).val(function(i, v) {
                                return v.replace('%','') + '%'; 
                            });
                            let plan_id = $('.select-update-plan').val();
                            let promo_value = $(this).val();
                            let form = new FormData();
                            let uri = "{{ route('admin.get.after.cal.plan.price') }}"
                            ajaxPostData(uri, {
                                'plan_id': plan_id,
                                'promo_value': promo_value
                            }, 'GET', '.updatePromoAfterCal', 'getAfterVal', '', true, '', false)
                            // $('.promo-val-err').remove();
                        }
                    }
                });
            
                // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
                var validatorUpdate = FormValidation.formValidation(
                    document.getElementById('kt_modal_update_promocode_form'), {
                        fields: {
                            'edit_promo_name': {
                                validators: {
                                    notEmpty: {
                                        message: 'Promo Code is required'
                                    }
                                }
                            },
                            'edit_plan_id[]': {
                                validators: {
                                    notEmpty: {
                                        message: 'Select the plan'
                                    }
                                }
                            },
                            'edit_promo_type': {
                                validators: {
                                    notEmpty: {
                                        message: 'Discount type is required'
                                    }
                                }
                            },
                            'edit_promo_value': {
                                validators: {
                                    notEmpty: {
                                        message: 'Discount Percentage is required'
                                    }
                                }
                            },
                        },

                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: '.fv-row',
                                eleInvalidClass: '.promo-type-error',
                                eleValidClass: ''
                            })
                        }
                    }
                );
                $(".select-update-plan").on('change', function() {
                    if (validatorUpdate) {
                        validatorUpdate.revalidateField('edit_plan_id[]');
                    }
                    let plan_id = $('.select-update-plan').val();
                    let promo_value = $("input[name='edit_promo_value']").val();
                    let form = new FormData();
                    let uri = "{{ route('admin.get.after.cal.plan.price') }}"
                    ajaxPostData(uri, {
                        'plan_id': plan_id,
                        'promo_value': promo_value
                    }, 'GET', '.updatePromoAfterCal', 'getAfterVal', '', true, '', false)
                });
                // Submit button handler
                var submitButtonUpdate = document.getElementById('kt_modal_update_promocode_submit');
                submitButtonUpdate.addEventListener('click', function(e) {
                    // Prevent default button action
                    e.preventDefault();

                    // Validate form before submit
                    if (validatorUpdate) {
                        validatorUpdate.validate().then(function(status) {
                            console.log('validated!');
                            // let promoValue = $('input[name="promo_value"]');
                            // if (promoValue.val() == null || promoValue.val() == "") {
                            //     $('.promo-val-err').remove();
                            //     $('<div class="fv-plugins-message-container invalid-feedback promo-val-err"><div data-field="promo_name" data-validator="notEmpty">Promo value is required</div></div>').insertAfter(promoValue);
                            //     return false;
                            // }else{
                            //     $('.promo-val-err').remove();
                            // }

                            if (status == 'Valid') {
                                // Show loading indication
                                submitButtonUpdate.setAttribute('data-kt-indicator', 'on');

                                // Disable button to avoid multiple click
                                submitButtonUpdate.disabled = true;
                                setTimeout(() => {
                                    document.getElementById('kt_modal_update_promocode_form')
                                .submit(); // Submit form
                                }, 1000);

                            }
                        });
                    }
                });
            </script>
