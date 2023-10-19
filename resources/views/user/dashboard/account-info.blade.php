@extends('user.dashboard.dashboard')

@section('title')
    Info
@endsection

@section('css')
    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
    <style>
        .modal-footerbtm {
            display: flex !important;
            flex-wrap: wrap !important;
            flex-shrink: 0;
            align-items: center !important;
            justify-content: flex-end !important;
        }

        .iti--allow-dropdown .iti__flag-container,
        .iti--separate-dial-code .iti__flag-container {
            right: auto;
            left: inherit;
        }

        .iti {
            padding-right: 0;
            padding-left: 0;
        }

        #webcam, #webcam video {
            width: 100% !important;
            height: fit-content !important;
        }

        @media screen and (max-width: 568px) {
            #webcam, #webcam video {
                width: 100% !important;
                height: fit-content !important;
            }
        }

        #webcam2, #webcam2 video {
            width: 100% !important;
            height: fit-content !important;
        }

        @media screen and (max-width: 568px) {
            #webcam2, #webcam2 video {
                width: 100% !important;
                height: fit-content !important;
            }
        }

        .upload-documents {
            border: none;
        }

        .upload-documents2 {
            border: 1px solid var(--text-color);
            border-radius: 25px;
        }

        .your-photo-wrapper:hover .edit {
            display: block;
        }

        .edit {
            padding-top: 7px;
            padding-right: 7px;
            position: absolute;
            right: 0;
            top: 0;
            display: none;
        }

        .edit a {
            color: #000;
        }

        .overlay {
            position: relative;
        }

        .overlay .overlay-layer {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5) !important;
            transition: all 0.3s ease;
            border-radius: 14px;
            opacity: 0;
        }

        .overlay.overlay-show .overlay-layer, .overlay.overlay-block .overlay-layer, .overlay:hover .overlay-layer {
            transition: all 0.3s ease;
            opacity: 1;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center mx-0 px-0">
        <div class="col-12 col-md-10">
            <div class="personal-photos">
                <p class="t-semi">Your photos</p>

                <div class="your-photos d-flex flex-column flex-md-row">
                    @php
                        $selfie = getImage($user['selfie']);
                        $govtId = getImage($user['govt_id']);
                    @endphp
                    <div>
                        <div id="div-img-selfie" class="your-photo-wrapper overlay mb-2">
                            <img src="{{ empty($user['selfie']) ? asset('/images/svg/bg_grey.svg') : $selfie }}"
                                class="your-photo" alt="" >

                            <div class="overlay-layer card-rounded bg-opacity-25 shadow">
                                @if (!empty($user['selfie']))
                                    <i class="fas fa-eye text-white me-3" onclick="showImageModal('{{ $selfie }}')"></i>
                                @endif
{{--                                <i class="fas fa-edit text-white" data-bs-toggle="modal" data-bs-target="#selfieModal"></i>--}}
                                <i class="fas fa-edit text-white" id="btn-capture-selfie" data-form="form-selfie"></i>
                            </div>
                        </div>
                        <p>Selfie</p>
                    </div>
                    <div>
                        <div id="div-img-govt-id" class="govt-photo-wrapper overlay mb-2">
                            <img src="{{ empty($user['govt_id']) ? asset('/images/svg/bg_grey.svg') : $govtId }}"
                                class="govt-photo" alt="">
                            <div class="overlay-layer card-rounded bg-opacity-25 shadow">
                                @if (!empty($user['govt_id']))
                                    <i class="fas fa-eye text-white me-3" onclick="showImageModal('{{ $govtId }}')"></i>
                                @endif
                                <i class="fas fa-edit text-white" data-bs-toggle="modal" data-bs-target="#govtIdModal"></i>
                            </div>
                        </div>
                        <p>Govt. ID</p>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary mb-4">
            <div class="personal-info">
                <div class="d-flex justify-content-between mb-4">
                    <p class="t-semi">Personal information</p>
                    <div class="form-group d-none d-lg-block">
                        <button type="button" class="btn btn-peaks" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                            Edit
                        </button>
                    </div>
                </div>

                <div class="row justify-content-center mx-0 px-0">

                    <div class="col-12 px-0">
                        <div class="text-center mb-4">
                            <div class="row">
                                <div class="col-12 px-0">
                                    <div class="row">

                                        <div class="col-12 col-lg-6 px-4">
                                            <div class="row mb-3">
                                                <label class="t-color text-start text-uppercase mb-2">
                                                    First Name
                                                </label>
                                                <input type="text" value="{{ $user->first_name }}"
                                                    class="input-field input-primary" readonly>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 px-4">
                                            <div class="row mb-3">
                                                <label class="t-color text-start text-uppercase mb-2">
                                                    Last Name
                                                </label>
                                                <input type="text" class="input-field input-primary"
                                                    value="{{ $user->last_name }}" readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 px-0">
                                    <div class="row">

                                        <div class="col-12 col-lg-6 px-4">
                                            <div class="row mb-3">
                                                <label class="t-color text-start text-uppercase mb-2">
                                                    Date of Birth
                                                </label>
                                                <input type="text" class="input-field input-primary"
                                                    value="{{ empty($user->dob) ? '-' : $user->dob }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 px-4">
                                            @if (Auth::user()->email_verified == 0)
                                            @endif
                                            <div class="row mb-3">
                                                <label class="t-color text-start text-uppercase mb-2">
                                                    Email Address
                                                </label>
                                                <input type="email" id="old-email" value="{{ $user->email }}"
                                                    class="input-field input-primary" readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 px-0">
                                    <div class="row">

                                        <div class="col-12 col-lg-6 px-4">
                                            @if (Auth::user()->phone_verified == 0)
                                            @endif
                                            <div class="row mb-3">
                                                <label class="t-color text-start text-uppercase mb-2">
                                                    Phone Number
                                                </label>
                                                <input type="tel" value="{{ $user->phone }}" id="old-phone"
                                                    class="input-field input-primary" readonly>
                                            </div>

                                        </div>

                                        <div class="col-12 col-lg-6 px-4">
                                            <div class="row mb-3">
                                                <label class="t-color text-start text-uppercase mb-2">
                                                    Gender
                                                </label>
                                                <input type="text"
                                                    value="{{ empty($user->gender) ? '-' : $user->gender }}"
                                                    class="input-field input-primary" readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4 d-block d-lg-none">
                                <button type="button" class="btn btn-peaks" data-bs-toggle="modal"
                                    data-bs-target="#editInfoModal">
                                    Edit
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select Selfie Modal -->
    <div class="modal fade" id="selfieModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-selfie-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-selfie" action="{{ route('upload-documents-post') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <ul id="ul-error"></ul>

                    <div class="modal-body px-4">
                        <div class="modal-details">

                            <div class="row mb-3 justify-content-around upload-documents2 p-3">
                                <div class="upload-documents col-12 col-md-7">
                                    <label for="selfie" class="text-start text-uppercase mb-2">Selfie</label>
                                    <input required id="selfie-input" type="file" name="selfie"
                                        accept="image/png, image/jpeg, image/jpg" data-form="form-selfie"
                                        onchange="readURL(this, '#selfie-preview')" />
                                        <div class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">
{{--                                            <div id="btn-capture-selfie" class="btn btn-peaks-outline btn-upload" data-form="form-selfie">--}}
{{--                                                Capture--}}
{{--                                            </div>--}}
{{--                                            <div id="btn-upload-selfie" class="btn btn-peaks-outline btn-upload">--}}
{{--                                                Upload--}}
{{--                                            </div>--}}
                                        </div>
{{--                                        <div class="mt-2 t-color-red t-small text-start text-error">Selfie should be in (.jpg, .jpeg, .png) format only.</div>--}}
                                </div>
                                <div class="preview-card col-12 col-md-5 p-0">
                                    <img id="selfie-preview" class="img-preview"
                                        src="{{ asset('/images/svg/bg_grey.svg') }}" alt="">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-evenly">
                        <button type="button" class="btn btn-peaks-outline btn-selfie-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-peaks loaderBtn">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Select Govt ID Modal -->
    <div class="modal fade" id="govtIdModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalHomePageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-gov-cancel" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-govt-id" action="{{ route('upload-documents-post') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <ul id="ul-error"></ul>

                    <div class="modal-body px-4">
                        <div class="modal-details">

                            <div class="row mb-3 justify-content-around upload-documents2 p-3">
                                <div class="upload-documents col-12 col-md-7">
                                    <label for="govt-id" class="text-start text-uppercase">
                                        Government issued photo id
                                    </label>
                                    <input id="govt-id-input" type="file" name="govt_id"
                                        accept="image/png, image/jpeg, image/jpg" data-form="form-govt-id"
                                        onchange="readURL(this, '#gov-id-preview')" required data-parsley-required="true"
                                        data-parsley-required-message="Govt. Id is required for verification.">
                                        <div class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">
                                            <div id="btn-capture-govt-id" class="btn btn-peaks-outline btn-upload" data-form="form-govt-id">
                                                    Capture
                                            </div>
                                            <div id="btn-upload-govt-id" class="btn btn-peaks-outline btn-upload">
                                                    Upload
                                            </div>
                                        </div>
                                    <div class="mt-2 t-small text-center status status__info">
                                        Note: Govt ID should be in (.jpg, .jpeg, .png) format only.
                                    </div>
                                </div>
                                <div class="preview-card col-12 col-md-5 p-0">
                                    <img id="gov-id-preview" class="img-preview"
                                        src="{{ asset('/images/svg/bg_grey.svg') }}" alt="">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-evenly">
                        <button type="button" class="btn btn-peaks-outline btn-gov-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-peaks loaderBtn">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Edit Info Modal -->
    <div class="modal fade" id="editInfoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalHomePageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title text-start" id="exampleModalLabel">Edit Info</h6>
                    <button type="button" class="btn-close updateCancelBtn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="account-info-form" action="{{ route('account-info-post') }}" method="POST">
                    @csrf

                    <ul id="ul-error"></ul>

                    <div class="modal-body px-4 pt-0">

                        <div class="col-12 px-0">
                            <div class="row">

                                <div class="col-12 col-lg-6 px-4">
                                    <div class="row mb-3">
                                        <label for="first_name" class="t-color text-start text-uppercase mb-2">
                                            First Name
                                        </label>
                                        <input @if ($orderExist == true) disabled @endif id="first_name" type="text" name="first_name"
                                            value="{{ $user->first_name }}"
                                            class="input-field input-primary @error('first_name') is-invalid @enderror"
                                            autocomplete="first_name" required data-parsley-required="true" placeholder="Enter first name">
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 px-4">
                                    <div class="row mb-3">
                                        <label for="last_name" class="t-color text-start text-uppercase mb-2">Last
                                            Name</label>
                                        <input @if ($orderExist == true) disabled @endif id="last_name" type="text"
                                            class="input-field input-primary @error('last_name') is-invalid @enderror"
                                            name="last_name" value="{{ $user->last_name }}" required
                                            autocomplete="last_name" data-parsley-required="true"
                                            placeholder="Enter last name"
                                            >
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 px-0">
                            <div class="row">

                                <div class="col-12 col-lg-6 px-4">
                                    <div class="row mb-3">
                                        <label for="dob" class="t-color text-start text-uppercase mb-2">
                                            Date of Birth
                                        </label>
                                        <input @if ($orderExist == true) disabled @endif id="dob" type="text"
                                            class="input-field input-primary  @error('dob') is-invalid @enderror"
                                            name="dob" value="{{ $user->dob }}" autocomplete="dob" required
                                            data-parsley-required="true"
                                            placeholder="Enter date of birth {MM/DD/YYYY}"
                                            >
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 px-4">
                                    <div class="row mb-3">
                                        <label for="email" class="t-color text-start text-uppercase mb-2">
                                            Email Address
                                        </label>
                                        <input id="email" type="email" name="email" value="{{ $user->email }}"
                                            class="input-field input-primary @error('email') is-invalid @enderror" required
                                            autocomplete="email" data-parsley-required="true"
                                            placeholder="Enter email"
                                            >
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 px-0">
                            <div class="row">

                                <div class="col-12 col-lg-6 px-4">
                                    <div class="row mb-3">
                                        <label for="phone" class="t-color text-start text-uppercase mb-2">
                                            Phone Number
                                        </label>
                                        <input @if ($isPhoneEnabled == false) disabled @endif id="phone" type="tel" value="{{ $user->phone }}" name="phone"
                                            class="input-field input-primary @error('phone') is-invalid @enderror"
                                            autocomplete="phone"
                                            placeholder="Enter phone number"
                                            >
                                        <span id="valid-msg" class="hide  text-start">✓ Valid</span>
                                        <span id="error-msg" class="error hide text-start"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 px-4">
                                    <div class="row mb-3">
                                        <label for="gender" class="t-color text-start text-uppercase mb-2">
                                            Gender
                                        </label>
                                        <select id="gender"
                                            class="input-field input-primary form-select @error('gender') is-invalid @enderror"
                                            name="gender" data-parsley-required="true" data-parsley-trigger="change"
                                            placeholder="Select gender"
                                            >
                                            <option value="" selected>Select</option>
                                            <option value="Male" @if ($user->gender == 'Male') selected @endif>Male
                                            </option>
                                            <option value="Female" @if ($user->gender == 'Female') selected @endif>Female
                                            </option>
                                            <option value="Others" @if ($user->gender == 'Others') selected @endif>Others
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer modal-footerbtm border-0 justify-content-evenly">
                        <button type="button" class="btn btn-peaks-outline updateCancelBtn"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-peaks submitBTN loaderBtn">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @include('auth.modals.capture')
    @include('auth.modals.gov-capture')
@endsection

@section('js')
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('mask/src/jquery.mask.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/webcam-easy/webcam-easy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/webcam-easy/initWebcam.js') }}"></script>

    <script>
        var input = document.querySelector("#phone");
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
        var iti = window.intlTelInput(input, {
            hiddenInput: "full_number",
            nationalMode: true,
            onlyCountries: ['us'],
            separateDialCode: true,
            utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
        });
    </script>
    <script src="{{ asset('intl-tel-input/build/js/load.js') }}"></script>
    <script>

        @if (session('message'))
        showToast('success', '{{ session('message') }}');
        @endif

        document.addEventListener('refreshPage', function (e) {
            setTimeout(() => {
                location.reload();
            }, 1000);
        }, false);

        $('#dob').mask('00/00/0000');
        @if ($orderExist == false)
        // $("#dob").datepicker({
        //     startDate: new Date('1900-01-01'),
        //     endDate: new Date(),
        //     autoclose: true,
        //     todayHighlight: true
        // });
        $("#dob").on('change', function () {
            if (verifyDate($('#dob').val()) == false) {
                $('#dob').val('');
            }
        });
        @endif

        const uploadDocument = function (formId) {
            return new Promise((resolve, reject) => {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let postUrl = '{{ route('upload-documents-post') }}';
                let form = document.getElementById(formId);
                let formData = new FormData(form);

                $.ajax({
                    type: "POST",
                    url: postUrl,
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend : function() {
                        $('.loaderElement').show();
                    },
                    success: (response) => {
                        $('.loaderElement').hide();
                        console.log(response)
                        if (response.status === 'success') {
                            showToast('success', response.message);
                            $('#govtIdModal').modal('hide')
                        } else {
                            showToast('error', response.message);
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: (error) => {
                        console.log(error)
                        $('.loaderElement').hide();
                        showAlert('error', error.message);
                        console.error(`error: ${error.message}`);
                    },
                });
            })
        }

        $(document).ready(function () {

            // $('#form-selfie').validate({ // initialize the plugin
            //     rules: {
            //         selfie: {
            //             required: true,
            //             extension: "jpg|jpeg|png"
            //         }
            //     },
            //     messages: {
            //         selfie: {
            //             required: "Please upload file.",
            //             extension: "Please upload file in these format only (jpg, jpeg, png)."
            //         }
            //     },
            //     submitHandler: function(form) {
            //         var file = $('input[name="selfie"]').prop('files')[0];
            //         if (file == undefined || file == null) {
            //             Swal.fire('', 'Selfie must be necessary', 'error')
            //             return false;
            //         }
            //         var fileType = file["type"];
            //         var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
            //         if ($.inArray(fileType, validImageTypes) < 0) {
            //             Swal.fire('', 'Please upload file in these format only (jpg, jpeg, png).', 'error')
            //             return false;
            //         }
            //         form.submit();
            //         $('.loaderElement').show();
            //         var loaderbtn = $('.loaderBtn');
            //         loaderbtn.attr('disabled', true);
            //     }
            // });

            $('#form-govt-id').submit(function (event) {
                event.preventDefault();

                uploadDocument('form-govt-id');
            })

            // $('#form-govt-id').validate({ // initialize the plugin
            //     rules: {
            //         govt_id: {
            //             required: true,
            //             extension: "jpg|jpeg|png"
            //         }
            //     },
            //     messages: {
            //         govt_id: {
            //             required: "Please select a valid file.",
            //             extension: "Please upload file in these format only (jpg, jpeg, png)."
            //         }
            //     },
            //     submitHandler: function (form) {
            //         var file = $('input[name="govt_id"]').prop('files')[0];
            //         if (file == undefined || file == null) {
            //             Swal.fire('', 'Government identification must be necessary', 'error')
            //             return false;
            //         }
            //         var fileType = file["type"];
            //         var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
            //         if ($.inArray(fileType, validImageTypes) < 0) {
            //             Swal.fire('', 'Please upload file in these format only (jpg, jpeg, png).', 'error')
            //             return false;
            //         }
            //         form.submit();
            //         $('.loaderElement').show();
            //         var loaderbtn = $('.loaderBtn');
            //         loaderbtn.attr('disabled', true);
            //     }
            // });

            $('#account-info-form').validate({ // initialize the plugin
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    first_name: {
                        required: true,
                        minlength: 3
                    },
                    last_name: {
                        required: true,
                        minlength: 3
                    },

                    dob: {
                        required: true
                    },
                    gender: {
                        required: true

                    }
                },
                messages: {
                    email: {
                        required: "Email is required."
                    },
                    first_name: {
                        required: "First name is required."
                    },
                    last_name: {
                        required: "Last name is required."
                    },

                    dob: {
                        required: "Date of birth is required."
                    },
                    gender: {
                        required: "Gender is required."
                    }
                }
            });

        });

        // $(document).on('keyup', "input[name='first_name']", function() {
        //     if ($("input[name='dob']") !== null && $("input[name='dob']") !== "") {
        //         $('#dob').datepicker('remove');
        //         $("input[name='dob']").attr('readonly',true)
        //     }
        // });
        // $(document).on('keyup', "input[name='last_name']", function() {
        //     if ($("input[name='dob']") !== null && $("input[name='dob']")  !== "") {
        //         $('#dob').datepicker('remove');
        //         $("input[name='dob']").attr('readonly',true)
        //     }
        // });
        // $(document).on('change', "input[name='dob']", function() {
        //    $("input[name='first_name']").attr('readonly',true)
        //    $("input[name='last_name']").attr('readonly',true)
        // });
        // Reload on cancel
        // $(document).on('click', ".updateCancelBtn", function() {
        //     window.location.reload();
        // });

        $(document).on('click', ".emailVerifyCancelBtn", function () {
            $('#verifyEmailBtn').removeAttr('disabled');
            $('#email').val($('#old-email').val());
            window.location.reload();
        });

        $(document).on('click', ".phoneVerifyCancelBtn", function() {

            $('#verifyPhoneBtn').removeAttr('disabled');
            $('#phone').val($('#old-phone').val());
            var iti = window.intlTelInput(input, {
                hiddenInput: "full_number",
                nationalMode: true,
                onlyCountries: ['us'],
                separateDialCode: true,
                utilsScript: "{{ asset('intl-tel-input/build/js/utils.js') }}",
            });
            window.location.reload();
        });

        $("#account-info-form").submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const postUrl = form.attr('action');
            var phone = $('#phone').val();
            if (phone == null || phone == undefined || phone == '') {

                $('#error-msg').html('')
                $('#error-msg').html('Phone number is required.')
                errorMsg.classList.remove("hide");
                return false;
            }
            if (!$("#account-info-form").valid()) {
                $("#account-info-form").validate();
                return false;
            }
            return new Promise(((resolve, reject) => {
                $.ajax({
                    method: "POST",
                    url: postUrl,
                    data: form.serialize(),
                    beforeSend: function(jqXHR, options) {
                        // setting a timeout
                        // $('.loaderElement').show();
                        var loaderbtn = $('.loaderBtn');
                        loaderbtn.attr('disabled', true);
                        // loaderbtn.html(loaderEl);
                    },
                    success: (response) => {
                        $('#editInfoModal').modal('hide');

                        showToast('success', response.success);

                        if (response.data == 'email_changed') {
                            $('.changedEmail').html($('#email').val());
                            $('#editInfoModal').modal('hide');
                            $('#VerifyEmail').modal('show');
                            var uri = "{{ route('email-resend-verify-ajax') }}";
                            var token = "{{ csrf_token() }}";
                            ajaxPostData(uri, {
                                '_token': token
                            }, 'POST', '', 'otpAjax')
                        }
                        if (response.data == 'phone_changed') {
                            $('.changedPhone').html($('input[name=full_number]').val());
                            $('#editInfoModal').modal('hide');
                            $('#VerifyPhone').modal('show');
                            var uri = "{{ route('resend.otp.ajax') }}";
                            var token = "{{ csrf_token() }}";
                            ajaxPostData(uri, {
                                '_token': token
                            }, 'POST', '', 'otpAjax')
                            // window.location.href = "{{ route('resend.otp') }}"
                        }
                        if (response.data == 'account_updated') {
                            setTimeout(() => {
                                window.location.reload();
                            }, 3500);
                        }
                    },
                    error: function(jqXHR, error, errorThrown) {
                        if (jqXHR.responseJSON.message == 'CSRF token mismatch.') {
                            var hostname = window.location.origin
                            window.location.replace(hostname + '/user/login');
                        }
                        if (jqXHR.status && jqXHR.status == 400) {
                            $.each(jqXHR.responseJSON.errors, function(key, value) {
                                Swal.fire({
                                    text: value,
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                })

                            });

                            //Swal.fire(jqXHR.responseText, '', 'warning')

                        } else {
                            var errorLog = JSON.parse(jqXHR.responseText);
                            if (errorLog.errors.email) {
                                Swal.fire(errorLog.errors.email[0], '', 'warning')
                            }
                            if (errorLog.errors.phone) {
                                Swal.fire(errorLog.errors.phone[0], '', 'warning')
                            }
                        }
                    },
                    complete: function() {
                        var loaderbtn = $('.loaderBtn');
                        loaderbtn.attr('disabled', false);
                    }
                })
            }));
        });

        $('#btn-upload-govt-id').click(() => {
            $('#govt-id-input').click();
        });
        $('.btn-gov-cancel').click(() => {
            $('#govt-id-input').val('');

            let url = "{{ asset('/images/svg/bg_grey.svg') }}";
            let previewTagId = '#gov-id-preview';

            $(previewTagId).attr('src', url);
        });
        $('#btn-upload-selfie').click(() => {
            $('#selfie-input').click();
        });
        $('.btn-selfie-cancel').click(() => {
            $('#selfie-input').val('');
            let url = "{{ asset('/images/svg/bg_grey.svg') }}";
            let previewTagId = '#selfie-preview';

            $(previewTagId).attr('src', url);
        });

        function readURL(input, previewTagId) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $(previewTagId).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function showImageModal(url) {
            let html = `<img src="${url}" style="height: 450px; object-fit: contain;" alt="User document"/>`;
            Swal.fire({
                // imageUrl: url,
                html: html,
            });
        }

        // $('#documents_upload_form').parsley().on('field:validated', function() {
        //     let ok = $('.parsley-error').length === 0;
        // }).on('field:submit', function() {
        //     console.log('submit form');
        //     return false;
        // });
    </script>
@endsection
