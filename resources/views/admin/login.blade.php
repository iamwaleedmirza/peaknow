<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head><base href="">
        <title>Admin | Login | @if($site_setting && $site_setting->site_title){{$site_setting->site_title}}@else{{ config('app.name', 'PeaksCurative') }}@endif</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta name="robots" content="noindex" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="PeaksCurative Admin Portal" />
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--end::Page Vendor Stylesheets-->
        <!--begin::Global Stylesheets Bundle(used by all pages)-->

        <link class="dark-css" href="{{asset('/admin_assets/css/preloader.css')}}" rel="stylesheet" type="text/css" />
        @if (isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] == '1')
        <link class="dark-css" href="{{asset('/admin_assets/assets/plugins/global/plugins.dark.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link class="dark-css" href="{{asset('/admin_assets/assets/css/style.dark.bundle.css')}}" rel="stylesheet" type="text/css" />

           <style>
            :root {
                --peaks-logo: url({{ asset('logo/Logo-Purple.svg') }});
            }

            body.dark-mode {
                --peaks-logo: url({{ asset('logo/Logo-white.svg') }});
            }
        </style>
            @else
            <link class="light-css" href="{{asset('/admin_assets/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
            <link class="light-css" href="{{asset('/admin_assets/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
            <style>
                :root {
                    --peaks-logo: url({{ asset('logo/Logo-Purple.svg') }});
                }

                body.dark-mode {
                    --peaks-logo: url({{ asset('logo/Logo-white.svg') }});
                }
                .peaks-logo {
                    width: auto;
                    height: 50px;
                    content: var(--peaks-logo);
                }
                .hideForLogin{
                    display: none !important;
                }
                .error{
                    color: red;
                }
                .spin-loader{
                    position: absolute;
                    left: 50%;
                    top: 43%;
                }
                .spinner-border{
                    display: block;
                    margin-left: 12px;
                }
            </style>

            @endif
        <!--end::Global Stylesheets Bundle-->

        @yield('css')
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled  @if (isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] == '1') dark-mode @endif" data-kt-app-page-loading="on">
        <x-ui.preloader/>
        <!--begin::Main-->
        <!--begin::Root-->
        <div class="loaderElement blockui-overlay" style="display: none; z-index: 999;position: fixed">
            <div class="blockui-message">
                <i class="fas fa-circle-notch fa-spin  text-primary" style="font-size: 22px;margin-right: 8px;"></i> Please Wait
            </div>
        </div>
        <div class="d-flex flex-column flex-root">
            <!--begin::Page-->


            <!--begin::Authentication - Sign-in -->
             <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url('{{asset('admin_assets/assets/media/illustrations/sketchy-1/14.png')}}')">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                    <!--begin::Logo-->
                    <a class="navbar-brand header-logo ms-5" href="{{ (\Auth::user()) ? route('admin.home') : route('admin.login') }}">
                        <div class="peaks-logo mb-4"></div>
                    </a>

                    <!--end::Logo-->
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                        <!--begin::Form-->
                        <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-8">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3">Admin Login</h1>
                                <!--end::Title-->
                             </div>
                            <!--begin::Heading-->
                            @error('email')
                            <div class="alert alert-danger text-center">
                                 {{ $message }}
                            </div>
                            @enderror

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-lg form-control-solid" placeholder="Enter email" type="text" name="email" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack mb-2">
                                    <!--begin::Label-->
                                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                                    <!--begin::Main wrapper-->
                                    <div class="fv-row" data-kt-password-meter="false">
                                        <!--begin::Wrapper-->
                                        <div class="mb-1">
                                          <!--begin::Input wrapper-->
                                            <div class="position-relative mb-3">
                                                <input class="form-control form-control-lg form-control-solid"
                                                    type="password" placeholder="Enter password" name="password" autocomplete="off" />
                                                <!--begin::Visibility toggle-->
                                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                    data-kt-password-meter-control="visibility">
                                                    <i class="bi bi-eye-slash fs-2"></i>

                                                    <i class="bi bi-eye fs-2 d-none"></i>
                                                </span>
                                                <!--end::Visibility toggle-->
                                            </div>
                                            @error('password')
                                            <div class="alert alert-danger text-center">
                                                 {{ $message }}
                                            </div>
                                            @enderror
                                            <!--end::Input wrapper-->
                                             <!--begin::Highlight meter-->
                                    <div class="d-flex align-items-center mb-3" style="display: none !important; " data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>
                                    <!--end::Highlight meter-->
                                          </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Main wrapper-->
                            </div>
                            <!--end::Input group-->
                            {{-- @if(env('APP_ENV') == 'production' || env('APP_ENV') == 'staging' || env('APP_ENV') == 'local')
                            <div class="form-group">
                                <div class="d-flex flex-column align-items-center">
                                    {!!  GoogleReCaptchaV3::renderField('admin_login_id', 'admin_login') !!}
                                </div>
                            </div> --}}
                            <div class="g-recaptcha" data-sitekey="{{env('RECAPTCHAV3_SITEKEY')}}" data-size="invisible">
                            </div>
                            <br>
                            {{--@endif--}}
                            <!--begin::Actions-->
                            <div class="text-center">
                                <!--begin::Submit button-->
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5" disabled>
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Submit button-->
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <div class="form-check form-switch form-check-custom fw-bold mt-4">
                        <input class="form-check-input h-20px w-40px check-darkmode" type="checkbox" @if (isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] == '1')  checked @endif id="flexSwitchDefault"/>
                        <label class="form-check-label" for="flexSwitchDefault">
                            Dark Mode
                        </label>
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->

            </div>
            <div class="page d-flex flex-row flex-column-fluid hideForLogin">
                <!--begin::Aside-->
                @yield('navbar')

                <!--end::Aside-->
                <!--begin::Wrapper-->
                <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                    <!--begin::Header-->
                    @yield('header')

                    <!--end::Header-->
                    <!--begin::Content-->
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        <!--begin::Post-->
                        <div class="post d-flex flex-column-fluid" id="kt_post">
                          <!--begin::Container-->
                          <div id="kt_content_container" class="container-xxl">
                            @yield('content')
                          </div>
                          <!--end::Container-->
                        </div>
                        <!--end::Post-->
                      </div>

                    <!--end::Content-->
                    <!--begin::Footer-->
                    @yield('footer')

                    <!--end::Footer-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::Root-->

        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
            <span class="svg-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
                    <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Scrolltop-->
        <!--begin::Modals-->
        <!--end::Modals-->
        <!--begin::Javascript-->
        <script>var hostUrl = "admin_assets/assets/";</script>
        <!--begin::Global Javascript Bundle(used by all pages)-->

        <script src="{{asset('/admin_assets/assets/plugins/global/plugins.bundle.js')}}"></script>
        <script src="{{asset('/admin_assets/assets/js/scripts.bundle.js')}}"></script>

        <script>
            function setCookie(name,value,days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days*24*60*60*1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }
           $(document).on('click', '.check-darkmode', function() {
                if($(this).is(':checked')){
                    KTApp.setThemeMode("dark", function() {
                        setCookie('dark-mode','1',365)
                    console.log("changed to dark mode");
                });
                }else{
                KTApp.setThemeMode("light", function() {
                    setCookie('dark-mode','0',365)
                    console.log("changed to light mode");
                });
                }

           });
        </script>
        <script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/additional-methods.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute();
        });
    </script>
    <script>
        
        setInterval(() => {
            grecaptcha.execute();
            // refreshReCaptchaV3('admin_login_id','admin_login');
            console.log('captcha');
        }, 30000);
        $(document).on("click",".toggle-password", function() {
            let input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $(this).addClass("fa-eye-slash");
                $(this).removeClass("fa-eye");
            } else {
                input.attr("type", "password");
                $(this).addClass("fa-eye");
                $(this).removeClass("fa-eye-slash");
            }
        });

        $("#kt_sign_in_form").validate({
            rules: {
                email: {
                    required: true,
                    email:true
                },
                password: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "Email is required"
                },
                password: {
                    required: "Password is required"
                },
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form, event) { // for demo
                event.preventDefault();
                var formData = new FormData(form);
                form.submit();
            }
        });

    window.onload = function () {
        $("#kt_sign_in_submit").prop('disabled',false);
        document.querySelector("#preloader").style.display = "none";
    }

    window.onbeforeunload = function () { $("#kt_sign_in_submit").prop('disabled',true) }
    </script>
        <!--end::Page Custom Javascript-->
        <!--end::Javascript-->
    </body>
    <!--end::Body-->
</html>
