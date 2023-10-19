<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | @if ($site_setting && $site_setting->site_title){{ $site_setting->site_title }}@else{{ config('app.name', 'PeaksCurative') }}@endif</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

    <!-- Tab Icon -->
    <link rel="icon" href="{{ asset('favicon.png') }}">

    <!-- CSS files -->
    <link href="{{ asset('/css/normalize/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('/css/peaks/root.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-pincode-input.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/peaks/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/peaks/sweetalert-modify.css') }}" rel="stylesheet">
    <style>
        :root {
            --peaks-logo: url({{ asset('logo/Logo-white.svg') }});
        }

        body.light-theme {
            --peaks-logo: url({{ asset('logo/Logo-Purple.svg') }});
        }

        @media screen and (max-width: 532px) {
            .footer-btn {
                position: relative !important;
                right: 0 !important;
                float: none !important;
            }
        }

    </style>

    @yield('css')

</head>

<body>
<div class="loaderElement blockui-overlay" style="display: none;">
    <div class="blockui-message">
        <i class="fas fa-circle-notch fa-spin" style="font-size: 22px;margin-right: 8px;"></i> <span id="loaderElementText">Please Wait</span>
    </div>

</div>
<nav class="row justify-content-center p-3 mx-0 mb-3">
    <div class="nav-header d-flex justify-content-center align-items-center">
        <a class="navbar-brand header-logo ms-5"
           href="{{ \Auth::user() ? route('account-home') : route('home') }}">
            <div class="peaks-logo"></div>
        </a>
        <div class="theme-toggle">
            <i id="theme-toggle" class="fas fa-sun change-theme"></i>
        </div>
        @if (Auth::check())
            @if (Auth::user()->phone_verified == 0 && Auth::user()->firstsiginup == 1)
                <button type="button" style="float: right;position: absolute;right: 40px;"
                        onclick="window.location.replace('{{ route('user.logout') }}');"
                        class="footer-btn btn btn-small btn-peaks">Logout
                </button>
            @elseif (Auth::user()->email_verified == 0 && Auth::user()->firstsiginup == 1)
                <button type="button" style="float: right;position: absolute;right: 40px;"
                        onclick="window.location.replace('{{ route('user.logout') }}');"
                        class="footer-btn btn btn-peaks btn-small">Logout
                </button>
            @endif
        @endif

    </div>
</nav>

<main id="app">
    @yield('content')
</main>

<!-- Cookie Policy PopUp -->
{{--
<div class="banner cookie_allow" style="display: none;">
    <div class="cookie-policy-banner flex-column flex-md-row gap-3">
        <p class="mb-0 me-3">
            This website uses cookies to ensure you get the best experience on our website PeaksCurative
            <a href="#" target="_blank">Cookie Policy.</a>
        </p>
        <button class="btn btn-peaks-hollow btn-small" onclick="acceptCookie()">Allow</button>
    </div>
</div>
--}}

<!-- JS Files -->
<script src="{{ asset('/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/js/parsley.min.js') }}"></script>
<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom JS -->
<script src="{{ asset('js/peaks/main.js') }}"></script>
<script src="{{ asset('/js/core.js') }}"></script>
@if (env('APP_ENV') == 'production')
    <script src="{{ asset('/js/utils/utils.js') }}"></script>
@endif

<script>
    $(document).on('click', '.close', function () {
        $(this).closest('div').css('display', 'none');
    });

    $(document).on("click", ".toggle-password", function() {
        let input = $($(this).data("toggle"));
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

</script>
<script>

   
    $(document).ready(function () {
        @php
            $cookie_agreed = \Cookie::get('cookie_agreed');
        @endphp
        @if ($cookie_agreed != 'yes')
        $(".cookie_allow").css("display", "inline-flex");
        @endif
    });
</script>

@yield('js')

@if (env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')
    <script>
        $(function () {
            function rescaleCaptcha() {
                let width = $('.g-recaptcha').parent().width();
                let scale;
                if (width < 302) {
                    scale = width / 302;
                } else {
                    scale = 1.0;
                }
                $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
                $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
                $('.g-recaptcha').css('transform-origin', '0 0');
                $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
            }

            rescaleCaptcha();
            $(window).resize(function () {
                rescaleCaptcha();
            });
        });
        $(document).on("submit", "#registration_form", function () {
            let response = grecaptcha.getResponse();
            if (response.length == 0) {
                $("#captcha-error").css("display", "block");
                return false;
            } else {
                $("#captcha-error").css("display", "none");
                return true;
            }
        });
        @if(session()->has('error_title') && session()->has('error_description'))
                Swal.fire({
                    title:'{{ session()->get('error_title') }}',
                    html: '{{ session()->get('error_description') }}',
                    icon: 'error',
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
                    customClass:{
                        icon: 'danger-error-icon',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // window.location.replace('{{route("account-home")}}');
                    }
                });
    @endif
    </script>
@endif

</body>

</html>
