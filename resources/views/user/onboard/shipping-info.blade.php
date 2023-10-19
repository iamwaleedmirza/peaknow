@extends('user.base.main')

@section('title') Shipping Address @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-10 col-md-7">

                @if(count($errors) > 0 )
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="p-0 m-0" style="list-style: none;">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{route('order-address-submit')}}" method="post">
                    @csrf
                    <div class="mb-4">
                        <h2 class="h-color">Shipping info</h2>
                    </div>

                    <div class="mb-4">

                        <div id="addresses" class="addresses">
                            @if(count(Auth::user()->addresses) > 0)
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="text-start mb-0">My Addresses</h6>
                                    <button class="btn btn-peaks btn-small" type="button" data-bs-toggle="modal"
                                            data-bs-target="#addressModal">
                                        Add new address
                                    </button>
                                </div>

                                @php $first_loop = true;  @endphp
                                @foreach(Auth::user()->getLatestAddresses()->get() as $address)
                                    <div class="address-card d-flex align-items-center mb-3">
                                        <div class="form-check px-3 py-3">
                                            <input class="form-check-input mt-2" type="radio"
                                                   id="shipping_address_{{ $address->id }}" name="shipping_address"
                                                   value="{{ $address->id }}"
                                                   @if($first_loop) checked @endif>
                                            <label class="form-check-label text-start ms-3"
                                                   for="shipping_address_{{ $address->id }}">
                                                {{ Auth::user()->first_name }}  {{ Auth::user()->last_name }} <br>
                                                <div class="d-flex">
                                                    <div>
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="mb-1">
                                                            {{ $address->address_line }}{{ $address->address_line2?', '.$address->address_line2.',':'' }} {{ $address->city.', '.$address->state.' - '.$address->zipcode }}
                                                        </p>
                                                    </div>
                                                </div>


                                                <p class="mb-0"><i
                                                        class="fas fa-phone-alt"></i> {{ Auth::user()->phone }}</p>
                                            </label>
                                        </div>
                                    </div>
                                    @php $first_loop = false;  @endphp
                                @endforeach

                                <div class="mb-5 mt-5">
                                    <button type="submit" class="btn btn-peaks submitBtn">Make Payment</button>
                                </div>

                            @else
                                <p class="text-center h4 my-5">
                                    Please add your shipping address.
                                </p>
                            @endif
                        </div>
                    </div>

                </form>
                @if(count(Auth::user()->addresses) == 0)
                    <div class="mb-3">
                        <button class="btn btn-peaks-outline" data-bs-toggle="modal" data-bs-target="#addressModal">
                            Add new address
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

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

                <form id="form_add_shipping_address" action="{{route('add-shipping-address')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        {{-- <div class="form-group row mb-3">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input type="text" name="name" class="input-field input-primary" placeholder="Name"
                                       data-parsley-required="true" data-parsley-pattern="/^[A-Za-z ]+$/"
                                       data-parsley-required-message="Please enter your name"
                                       value="{{Auth::user()->first_name.' '.Auth::user()->last_name}}"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="tel" id="phone" name="phone" class="input-field input-primary"
                                       placeholder="Phone number" data-parsley-required="true"
                                       data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-trigger="change"
                                       data-parsley-required-message="Please enter your phone number" value="{{Auth::user()->phone}}"/>
                                       <span id="error-msg" class="hide text-start"></span>

                            </div>
                        </div> --}}
                        <div class="form-group mb-3">
                            <input type="text" name="address_line" class="input-field input-primary"
                                   placeholder="Street 1" data-parsley-required="true"
                                   data-parsley-maxlength="50"
                                   data-parsley-maxlength-message="Street 1 address should be 50 characters or fewer."
                                   data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
                                   data-parsley-required-message="Please enter your address"/>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" name="address_line2" class="input-field input-primary"
                                   placeholder="Street 2"
                                   data-parsley-maxlength="50"
                                   data-parsley-maxlength-message="Street 2 address should be 50 characters or fewer."
                                   data-parsley-pattern="^[a-zA-Z0-9/,.&quot;\s,'-]*$"
                                   data-parsley-required-message="Please enter your street 2 address"/>
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
                                       data-parsley-required="true"
                                       data-parsley-type="number" data-parsley-trigger="change"
                                       data-parsley-required-message="Please enter your zipcode"/>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            @php $states = getActiveStates(); @endphp
                            <select class="input-field input-primary" name="state" data-parsley-required="true"
                                    data-parsley-required-message="Please select your state">
                                <option value="">Select state</option>
                                @foreach($states as $state)
                                    <option value="{{$state->state_code}}">{{$state->state}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-sm btn-peaks-outline" data-bs-dismiss="modal"
                                aria-label="Close">Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-peaks saveBTN">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('mask/src/jquery.mask.js') }}"></script>
{{--
    <script>
        var input = document.querySelector("#phone");
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        var iti = window.intlTelInput(input, {

            hiddenInput: "full_number",

            nationalMode: true,
            onlyCountries: ['us', 'pr'],

            separateDialCode: true,
            utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
        });
    </script>
    <script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script> --}}
    <script>
        $('.zipCode').mask('00000');
        $(document).ready(function () {
            @if(session()->has('success'))
            showToast('success', '{{ session()->get('success') }}')
            @endif
        });

        $(document).on('click', ".submitBtn", function () {
            $('.loaderElement').show();
            @if (auth()->user()->payment_profile_id)
                $('#loaderElementText').text('Processing Payment...');
            @endif
        });

        $('#form_add_shipping_address').parsley().on('field:validated', function () {
            let ok = $('.parsley-error').length === 0;
        }).on('field:submit', function () {
            console.log('submit form');
            return false;
        });

        $("#form_add_shipping_address").submit(function (e) {
            e.preventDefault();
            const form = $(this);
            const postUrl = form.attr('action');

            return new Promise(((resolve, reject) => {
                $.ajax({
                    method: "POST",
                    url: postUrl,
                    data: form.serialize(),
                    beforeSend: function(jqXHR, options) {
                        // setting a timeout
                        $('.loaderElement').show();
                        var loaderbtn = $('.saveBTN');
                        loaderbtn.attr('disabled', true);
                        // loaderbtn.html(loaderEl);
                    },
                    success: (response) => {
                        location.reload();
                        $('.loaderElement').hide();
                        var loaderbtn = $('.saveBTN');
                        loaderbtn.attr('disabled', false);
                    },
                    error: function (xhr) {
                        var loaderbtn = $('.saveBTN');
                        loaderbtn.attr('disabled', false);
                        $('.loaderElement').hide();
                        $('#error-add-address').html('');
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('#error-add-address').append('<li class="text-danger">' + value + '</li>');
                        });
                    }
                })
            }));
        });
            @if(session()->has('error_title') && session()->has('error_description'))
                Swal.fire({
                    title:'{{ session()->get('error_title') }}',
                    html: '{{ session()->get('error_description') }}',
                    icon: 'error',
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Go Back',
                    customClass:{
                        icon: 'danger-error-icon',
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.replace('{{route("account-home")}}');
                    }
                });
            @endif
    </script>
@endsection
