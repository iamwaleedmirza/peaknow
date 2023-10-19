<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-start" id="exampleModalLabel">Edit Address</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <ul id="error-edit-address"></ul>

            <form id="form_edit_address" action="{{ route('user.order.shipping.address') }}" method="post">
                @csrf
                <input type="hidden" name="order_id" id="address_order_id" value="">
                <input type="hidden" name="address_type" id="address_type" value="edit">
                <div class="modal-body">
                    {{-- <div class="form-group row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <input readonly type="text" id="edit_name" name="edit_name" class="input-field input-primary"
                                   placeholder="Name" data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                   data-parsley-required-message="Please enter your name" />
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="tel" id="edit_phone" name="edit_phone"
                                   class="edit_phone input-field input-primary" placeholder="Phone number"
                                   data-parsley-required="true" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$"
                                   data-parsley-trigger="change"
                                   data-parsley-required-message="Please enter your phone number" />
                            <span id="error-msg" class="hide edit_error text-start"></span>
                        </div>
                    </div> --}}
                    <div class="form-group mb-3">
                        <input type="text" id="edit_address_line" name="edit_address_line"
                               class="input-field input-primary" placeholder="Street 1"
                               data-parsley-required="true"
                               data-parsley-maxlength="50"
                               data-parsley-maxlength-message="Street 1 address should be 50 characters or fewer."
                               data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
                               data-parsley-required-message="Please enter your address"/>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="edit_address_line2" name="edit_address_line2"
                               class="input-field input-primary" placeholder="Street 2"
                               data-parsley-maxlength="50"
                               data-parsley-maxlength-message="Street 2 address should be 50 characters or fewer."
                               data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
                               data-parsley-required-message="Please enter your address"/>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <input type="text" id="edit_city" name="edit_city" class="input-field input-primary"
                                   placeholder="City" data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                   data-parsley-required-message="Please enter your city"/>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="text" id="edit_zipcode" name="edit_zipcode"
                                   class="input-field input-primary zipCode"
                                   placeholder="Zipcode" data-parsley-required="true" data-parsley-type="number"
                                   data-parsley-trigger="change"
                                   data-parsley-required-message="Please enter your zipcode"/>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        @php
                            $states = getActiveStates();
                        @endphp
                        <select class="input-field input-primary" id="edit_state" name="edit_state"
                                data-parsley-required="true" data-parsley-required-message="Please select your state">
                            <option value="">Select state</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 text-center text-md-end">
                    <button type="button" class="btn btn-sm btn-peaks-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-peaks submitBTN">Update</button>
                </div>
                <div class="text-center mb-3">
                    <span class="t-link select_address"
                          data-bs-toggle="modal"
                          data-bs-target="#selectShippingAddressModal" onclick="$('#editAddressModal').modal('hide')"
                          data-id="{{$order->id}}"
                    >Select from existing</span>
                </div>
            </form>
        </div>
    </div>
</div>
