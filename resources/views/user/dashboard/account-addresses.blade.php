@extends('user.dashboard.dashboard')

@section('title')
    My Addresses
@endsection

@section('content')

    <div class="row justify-content-center mx-0 px-0">
        <div class="col-12 col-md-10 px-0">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Addresses</h5>
                <button class="btn btn-peaks btn-small" data-bs-toggle="modal" data-bs-target="#addressModal">
                    Add new address
                </button>
            </div>

            <div class="my-addresses">

                @if (count($addresses) > 0)
                    @foreach ($addresses as $address)
                        <div class="address">
                            <div class="d-flex flex-column flex-md-row justify-content-md-between gap-4">
                                <div>
                                    <p id="address_{{ $address->id }}" class="mb-1 t-bold">
                                        {{ Auth::user()->first_name }}  {{ Auth::user()->last_name }}
                                    </p>
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="mb-0">{{ $address->address_line }}</p>
                                            <p class="mb-0">{{ $address->address_line2 }}</p>
                                            <p class="mb-1">
                                                {{ $address->city . ', ' . $address->state . ' - ' . $address->zipcode }}</p>
                                        </div>
                                    </div>
                                    <p class="mb-0"><i class="fas fa-phone-alt"></i> {{ Auth::user()->phone }}</p>
                                </div>
                                <div class="d-flex justify-content-around gap-3">
                                    <div class="btn-address edit-address" data-id="{{ $address->id }}"
                                         data-name="{{ $address->name }}" data-phone="{{ $address->phone }}"
                                         data-address="{{ $address->address_line }}" data-city="{{ $address->city }}"
                                         data-address2="{{ $address->address_line2 }}"
                                         data-zipcode="{{ $address->zipcode }}" data-state="{{ $address->state }}"
                                         data-bs-toggle="modal" data-bs-target="#editAddressModal">
                                        <i class="fas fa-edit"></i>
                                        <p class="d-md-none mb-0">Edit</p>
                                    </div>

                                    <div class="btn-address" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                         onclick="deleteAddress('{{ $address->id }}')">
                                        <i class="fas fa-trash-alt"></i>
                                        <p class="d-md-none mb-0">Delete</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="d-flex justify-content-center align-items-center mt-0 mt-md-5">
                        <div class="card d-flex flex-column justify-content-center align-items-center p-4">
                            <img src="{{ asset('images/svg/address.svg') }}" class="img-empty-data mb-4" alt=""/>
                            <h6>You have no addresses added!</h6>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    <!-- Address Modal -->
    <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-start" id="exampleModalLabel">Add new shipping address</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <ul id="error-add-address"></ul>

                <form id="form_add_shipping_address" action="{{ route('add-shipping-address') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        {{-- <div class="form-group row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input type="text" name="name" class="input-field input-primary" placeholder="Name"
                                    data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                    data-parsley-required-message="Please enter your name"
                                    value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" />
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="tel" name="phone" id="phone" class="input-field input-primary"
                                    placeholder="Phone number" data-parsley-required="true"
                                    data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-trigger="change"
                                    data-parsley-required-message="Please enter your phone number"
                                    value="{{ Auth::user()->phone }}" />
                                    <span id="error-msg" class="hide text-start"></span>
                            </div>
                        </div> --}}
                        <div class="form-group mb-3">
                            <input type="text" name="address_line" class="input-field input-primary"
                                   placeholder="Street 1" data-parsley-required="true"
                                   data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
                                   data-parsley-required-message="Please enter your street 1 address"
                                   data-parsley-maxlength="50"
                                   data-parsley-maxlength-message="Street 1 address should be 50 characters or fewer."/>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" name="address_line2" class="input-field input-primary"
                                   placeholder="Street 2" data-parsley-maxlength="50"
                                   data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
                                   data-parsley-required-message="Please enter your street 2 address"
                                   data-parsley-maxlength-message="Street 2 address should be 50 characters or fewer."/>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input type="text" name="city" class="input-field input-primary" placeholder="City"
                                       data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                       data-parsley-required-message="Please enter your city"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="zipcode" class="input-field input-primary zipCode"
                                       placeholder="Zipcode"
                                       data-parsley-required="true" data-parsley-type="number"
                                       data-parsley-trigger="change"
                                       data-parsley-required-message="Please enter your zipcode"/>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            @php
                                $states = getActiveStates();
                            @endphp
                            <select class="input-field input-primary" name="state" data-parsley-required="true"
                                    data-parsley-required-message="Please select your state">
                                <option value="">Select state</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->state_code }}">{{ $state->state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 text-center text-md-end">
                        <button type="button" class="btn btn-sm btn-peaks-outline" data-bs-dismiss="modal"
                                aria-label="Close">Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-peaks submitBTN">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-start" id="exampleModalLabel">Delete Address</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('delete-shipping-address') }}" method="post">
                    @csrf
                    <input type="hidden" name="address_id" id="address_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <h6>Are you sure to delete this address?</h6>
                        </div>
                    </div>
                    <div class="modal-footer border-0 text-center text-md-end">
                        <button type="button" class="btn btn-sm btn-peaks-outline" data-bs-dismiss="modal">No</button>
                        <button id="btn-delete-address" type="submit" class="btn btn-sm btn-peaks">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-start" id="exampleModalLabel">Edit Address</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <ul id="error-edit-address"></ul>

                <form id="form_edit_address" action="{{ route('edit-shipping-address') }}" method="post">
                    @csrf
                    <input type="hidden" name="edit_address_id" id="edit_address_id" value="">
                    <div class="modal-body">
                        {{-- <div class="form-group row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input type="text" id="edit_name" name="edit_name" class="input-field input-primary"
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
                                   data-parsley-maxlength="50"
                                   data-parsley-maxlength-message="Street 1 address should be 50 characters or fewer."
                                   data-parsley-required="true" data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
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
                                       placeholder="City" data-parsley-required="true"
                                       data-parsley-pattern="/^[A-Za-z ]+$/"
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
                                    data-parsley-required="true"
                                    data-parsley-required-message="Please select your state">
                                <option value="">Select state</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->state_code }}">{{ $state->state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 text-center text-md-end">
                        <button type="button" class="btn btn-sm btn-peaks-outline" data-bs-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-peaks submitBTN">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('mask/src/jquery.mask.js') }}"></script>

    <script>
        // var input = document.querySelector("#phone");
        // errorMsg = document.querySelector("#error-msg"),
        //     validMsg = document.querySelector("#valid-msg");
        // var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        // var iti = window.intlTelInput(input, {
        //     hiddenInput: "full_number",
        //     nationalMode: true,
        //     onlyCountries: ['us'],
        //     separateDialCode: true,
        //     utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
        // });
    </script>
    <script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>


    <script>
        function deleteAddress(id) {
            $("#address_id").val(id);
        }

        // var edit_input = document.querySelector("#edit_phone");
        // $('#edit_phone').mask('000 000-0000');
        // $('.zipCode').mask('00000');
        // editerrorMsg = document.querySelector(".edit_error"),
        //         validMsg = document.querySelector("#valid-msg");
        //     var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        //     var edit_iti = window.intlTelInput(edit_input, {
        //         hiddenInput: "edit_full_number",
        //         nationalMode: true,
        //         onlyCountries: ['us'],
        //         separateDialCode: true,
        //         utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
        //     });
        $(document).on('click', '.edit-address', function () {
            $("#edit_address_id").val($(this).attr("data-id"));
            $("#edit_name").val($(this).attr("data-name"));
            // $(".edit_phone").val($(this).attr("data-phone"));
            // var edit_input = document.querySelector("#edit_phone");
            // editerrorMsg = document.querySelector(".edit_error"),
            //     validMsg = document.querySelector("#valid-msg");
            // var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
            // var edit_iti = window.intlTelInput(edit_input, {
            //     hiddenInput: "edit_full_number",
            //     nationalMode: true,
            //     onlyCountries: ['us'],
            //     separateDialCode: true,
            //     utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
            // });
            $("#edit_address_line").val($(this).attr("data-address"));
            $("#edit_address_line2").val($(this).attr("data-address2"));
            $("#edit_city").val($(this).attr("data-city"));
            $("#edit_zipcode").val($(this).attr("data-zipcode"));
            $("#edit_state").val($(this).attr("data-state"));
            // $('#edit_phone').mask('000 000-0000');


            // var reset = function() {
            //     edit_input.classList.remove("error");
            //     editerrorMsg.innerHTML = "";
            //     editerrorMsg.classList.add("hide");
            //     // validMsg.classList.add("hide");
            // };

            // // on blur: validate
            // edit_input.addEventListener('keyup', function() {
            //     reset();
            //     if (edit_input.value.trim()) {
            //         if (edit_iti.isValidNumber()) {
            //             $('.submitBTN').attr('type', 'submit')
            //             $('.submitBTN').removeAttr('disabled')
            //             // validMsg.classList.remove("hide");
            //         } else {
            //             $('.submitBTN').attr('type', 'button')
            //             $('.submitBTN').attr('disabled', 'true')

            //             edit_input.classList.add("error");
            //             var errorCode = edit_iti.getValidationError();
            //             editerrorMsg.innerHTML = errorMap[errorCode];
            //             // if (errorCode == -99) {
            //             //     errorMsg.innerHTML = 'Please enter valid mobile number'
            //             // }

            //             editerrorMsg.classList.remove("hide");
            //         }
            //     }
            // });
        });

        $('#form_add_shipping_address').parsley().on('field:validated', function () {
            let ok = $('.parsley-error').length === 0;
        }).on('field:submit', function () {
            console.log('submit form');
            return false;
        });

        $("#form_add_shipping_address").submit(function (e) {
            e.preventDefault();
            $('.loaderElement').show();
            const form = $(this);
            const postUrl = form.attr('action');

            return new Promise(((resolve, reject) => {
                $.ajax({
                    method: "POST",
                    url: postUrl,
                    data: form.serialize(),
                    success: (response) => {
                        $('.loaderElement').hide();
                        location.reload()
                    },
                    error: function (xhr) {
                        $('.loaderElement').hide();
                        if (xhr.responseJSON.message == 'CSRF token mismatch.') {
                            var hostname = window.location.origin
                            window.location.replace(hostname + '/user/login');
                        }
                        $('#error-add-address').html('');
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('#error-add-address').append(
                                '<li class="text-danger">' + value + '</li>');
                        });
                    }
                })
            }));
        });

        $('#form_edit_address').parsley().on('field:validated', function () {
            let ok = $('.parsley-error').length === 0;
        }).on('field:submit', function () {
            console.log('submit form');
            return false;
        });

        $("#form_edit_address").submit(function (e) {
            e.preventDefault();

            $('.loaderElement').show();
            const form = $(this);
            const postUrl = form.attr('action');
            return new Promise(((resolve, reject) => {
                $.ajax({
                    method: "POST",
                    url: postUrl,
                    data: form.serialize(),
                    success: (response) => {
                        $('.loaderElement').hide();
                        location.reload()
                    },
                    error: function (xhr) {
                        $('.loaderElement').hide();
                        if (xhr.responseJSON.message == 'CSRF token mismatch.') {
                            var hostname = window.location.origin
                            window.location.replace(hostname + '/user/login');
                        }
                        $('#error-edit-address').html('');
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('#error-edit-address').append(
                                '<li class="text-danger">' + value + '</li>');
                        });
                    }
                })
            }));
        });
    </script>
@endsection
