<div class="modal fade" id="kt_modal_add_promocode" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" method="POST" id="promocode_form">
                @csrf
                <input type="hidden" name="id" id="id" value="">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_promocode_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder" id="modal-title">Add Promo Code</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_add_promocode_close" class="btn btn-icon btn-sm btn-active-icon-primary close-modal">
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
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_promocode_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_promocode_header"
                        data-kt-scroll-wrappers="#kt_modal_add_promocode_scroll" data-kt-scroll-offset="300px">
                        <div class="row">
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <span class="required fs-6 fw-bold mb-3">Promo Code</span>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Enter Promo Code" name="promo_name" id="promo_name" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>


                        <!--begin::Input group-->
                        <div class="d-flex fv-row flex-column gap-2 mb-7">
                            <!--begin::Label-->
                            <!-- <label class="required fs-6 fw-bold mb-2">Discount Type</label> -->
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div>
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" type="radio" checked name="promo_type" id="promo_type1" value="0">
                                    <span class="form-check-label promo-text" for="0">Applicable to all plans</span>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="promo_type" id="promo_type2" value="1">
                                    <span class="form-check-label promo-text" for="1">For Particular Plan</span>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="promo_type" id="promo_type2" value="2">
                                    <span class="form-check-label promo-text" for="2">For Particular Product</span>
                                  </div> 
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="promo_type" id="promo_type3" value="3">
                                    <span class="form-check-label promo-text" for="3">For Particular Plan</span>
                                  </div> 
                                  <div class="form-check form-check-inline mt-4">
                                    <input class="form-check-input" type="radio" name="promo_type" id="promo_type4" value="4">
                                    <span class="form-check-label promo-text" for="4">For Particular Medicine Variant</span>
                                  </div> 
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <div class="row select-plan-data">
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <span class="required fs-6 fw-bold mb-2">Select Product</span>
                                    <select class="form-select" name="product_id" id="product_id">
                                        <option value="">Select Product</option>
                                        @foreach($row['product'] as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::span-->
                                    <span class="required fs-6 fw-bold mb-2">Select Plan Type</span>
                                    <select class="form-select" name="plan_id" id="plan_id">
                                        <option value="">Select Plan Type</option>
                                        @foreach($row['plan_detail'] as $plan)
                                            <option value="{{$plan->id}}">{{$plan->name}} ({{($plan->subscription_type==0) ? 'One Time' : 'Monthly Subscription'}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::span-->
                                    <span class="required fs-6 fw-bold mb-2">Select Medicine Variant</span>
                                    <select class="form-select" name="medicine_variant_id" id="medicine_variant_id">
                                        <option value="">Select Medicine Variant</option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>

                        <div class="row select-product-data">
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <span class="required fs-6 fw-bold mb-2">Select Product</span>
                                    <select class="form-select" name="select_product_id" id="select_product_id">
                                        <option value="">Select Product</option>
                                        @foreach($row['product'] as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>

                        <div class="row select-plans-data">
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::span-->
                                    <span class="required fs-6 fw-bold mb-2">Select Plan Type</span>
                                    <select class="form-select" name="select_plan_id" id="select_plan_id">
                                        <option value="">Select Plan Type</option>
                                        @foreach($row['plan_detail'] as $plan)
                                            <option value="{{$plan->id}}">{{$plan->name}} ({{($plan->subscription_type==0) ? 'One Time' : 'Monthly Subscription'}})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>

                        <div class="row select-medicine-data">
                            <div class="col">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::span-->
                                    <span class="required fs-6 fw-bold mb-2">Select Medicine Variant</span>
                                    <select class="form-select" name="select_medicine_variant_id" id="select_medicine_variant_id">
                                        <option value="">Select Medicine Variant</option>
                                        @foreach($row['medicine'] as $plan)
                                            <option value="{{$plan->id}}">{{$plan->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>

                        <!--begin::Input group-->
                        <div class="fv-row mb-2 promeValue" >
                            <span class="required fs-6 fw-bold mb-2">Discount Percentage</span>
                            <input type="text" id="promo_value_by_precent" class="form-control form-control-solid promo_value_by_precent" placeholder="Enter Discount Percentage" name="promo_value"
                            value="" onkeypress="return IsNumeric(event)" />
                        </div>
                        <div class="fv-plugins-message-container text-primary mb-7"><div data-field="promo_name" data-validator="notEmpty">Note: Discount percentage should be between 1% to 99%</div></div>

                        <div class="fv-row mb-7 promo_status">
                            <span class="required fs-6 fw-bold mb-2">Select Status</span>
                            <select class="form-select" aria-label="Select Status" name="promo_status" id="promo_status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        
                        
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="button" id="kt_modal_add_promocode_close" class="btn btn-light me-3 close-modal">Discard</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_add_promocode_submit" class="btn btn-primary kt_modal_promocode_submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>