@extends('user.base.main')

@section('title') Upload Documents @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <style>
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
    </style>
@endsection

@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-10 col-md-8 col-lg-8">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button>
                        <span>{{ $message }}</span>
                    </div>
                @endif

                <div class="mb-4">
                    <h2 class="h-color">Verify your identity</h2>
                    <p>We need you to confirm your identity with your documents.</p>
                </div>
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="error">{{$error}}</li>
                        @endforeach
                    </ul>
                @endif
                <form class="mb-4" method="POST" action="{{ route('upload-documents-post') }}"
                      id="documents_upload_form" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="verify_documents" value="true">

                    <div class="row mb-3 justify-content-around upload-documents2 p-3">
                        <div class="upload-documents col-12 col-md-7 mb-4">
                            <label for="govt-id" class="text-start text-uppercase">Government issued photo id</label>
                            <input id="govt-id-input" type="file" name="govt_id"
                                   accept="image/png, image/jpeg, image/jpg"
                                   onchange="readURL(this, '#gov-id-preview')">
                            <div
                                class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">
                                <div id="btn-capture-govt-id" class="btn btn-peaks-outline btn-upload"
                                     data-form="documents_upload_form">
                                    Capture
                                </div>
                                <div id="btn-upload-govt-id" class="btn btn-peaks-outline btn-upload">
                                    Upload
                                </div>
                            </div>
                            <div class="mt-2 t-small text-start status status__info">
                                Note: Govt ID should be in (.jpg, .jpeg, .png) format only.
                            </div>
                        </div>
                        <div class="preview-card col-12 col-md-5 p-0" style="width: auto">
                            <img id="gov-id-preview" class="img-preview"
                                 src="{{ !empty($documents['govt_id']) ? getImage($documents['govt_id']) : asset('/images/svg/bg_grey.svg') }}">
                        </div>
                    </div>

                    <div class="row mb-3 justify-content-around upload-documents2 p-3">
                        <div class="upload-documents col-12 col-md-7 mb-4">
                            <label for="selfie" class="text-start text-uppercase mb-2">Selfie</label>
                            {{--                            <input id="selfie-input" type="file" name="selfie" accept="image/png, image/jpeg"--}}
                            {{--                                   onchange="readURL(this, '#selfie-preview')">--}}
                            <div
                                class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">
                                <div id="btn-capture-selfie" class="btn btn-peaks-outline btn-upload"
                                     data-form="documents_upload_form">
                                    Capture
                                </div>
                                {{--                                <div id="btn-upload-selfie" class="btn btn-peaks-outline btn-upload">--}}
                                {{--                                    Upload--}}
                                {{--                                </div>--}}
                            </div>
                            {{--                            <div class="mt-2 t-color-red t-small text-start text-error">Selfie should be in (.jpg, .jpeg, .png) format only.</div>--}}
                        </div>
                        <div class="preview-card col-12 col-md-5 p-0">
                            <img id="selfie-preview" class="img-preview"
                                 src="{{ !empty($documents['selfie']) ? getImage($documents['selfie']) : asset('/images/svg/bg_grey.svg') }}">
                        </div>
                    </div>

                    <div class="form-group mb-5 mt-5">
                        <button type="submit" class="btn btn-peaks loaderBtn">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @include('auth.modals.capture')
    @include('auth.modals.gov-capture')
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/webcam-easy/webcam-easy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/webcam-easy/initWebcam.js') }}"></script>

    <script>

        @if (session('message'))
            showAlert('error', "{{ session('message') }}");
        @endif

        $('#btn-upload-govt-id').click(() => {
            $('#govt-id-input').click();
        });
        $('#btn-upload-selfie').click(() => {
            $('#selfie-input').click();
        });

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
                    processData: false,
                    beforeSend: function () {
                        $('.loaderElement').show();
                    },
                    success: (response) => {
                        $('.loaderElement').hide();
                        if (response.status === 'success') {
                            showToast('success', response.message);
                        } else {
                            showToast('error', response.message);
                        }
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

        function readURL(input, previewTagId) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $(previewTagId).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);

                uploadDocument('documents_upload_form');
            }
        }

        $('#documents_upload_form').on('submit', function (event) {
            event.preventDefault();

            $.get('{{ route('user.is-documents-uploaded') }}', function (data, status) {
                if (!data.is_govt_id) {
                    Swal.fire('', 'Government identification is required for verification.', 'error');
                    return false;
                }
                if (!data.is_selfie) {
                    Swal.fire('', 'Selfie is required for verification.', 'error');
                    return false
                }
                if (data.url != null) {
                    window.location.href = data.url;
                }
            });
        })

        // $('#documents_upload_form').validate({
        //     submitHandler: function(form) {
        // const govtIdFile = $('input[name="govt_id"]').prop('files')[0];
        // const selfieFile = $('input[name="selfie"]').prop('files')[0];
        // $('.loaderElement').show();
        {{--                @if (empty($documents['govt_id']))--}}
        {{--                if (govtIdFile == null) {--}}
        {{--                    Swal.fire('', 'Government identification is required for verification.', 'error');--}}
        {{--                    $('.loaderElement').hide();--}}
        {{--                    return false;--}}
        {{--                }--}}
        {{--                @endif--}}
        {{--                @if (empty($documents['selfie']))--}}
        {{--                if (selfieFile == null) {--}}
        {{--                    Swal.fire('', 'Selfie is required for verification.', 'error');--}}
        {{--                    $('.loaderElement').hide();--}}
        {{--                    return false;--}}
        {{--                }--}}
        {{--                @endif--}}

        // const validImageTypes = ["image/jpg", "image/jpeg", "image/png"];
        //
        // const govtIdFileType = govtIdFile["type"];
        // const selfieFileType = selfieFile["type"];

        // if ($.inArray(govtIdFileType, validImageTypes) < 0) {
        //     Swal.fire('', 'Govt ID should be in (.jpg, .jpeg, .png) format only.', 'error')
        //     $('.loaderElement').hide();
        //     return false;
        // }
        // if ($.inArray(selfieFileType, validImageTypes) < 0) {
        //     Swal.fire('', 'Selfie should be in (.jpg, .jpeg, .png) format only.', 'error')
        //     $('.loaderElement').hide();
        //     return false;
        // }

        // form.submit();
        // }
        // });

    </script>
@endsection
