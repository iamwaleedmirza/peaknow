<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

    <title>@yield('title')
        | @if ($site_setting && $site_setting->site_title){{ $site_setting->site_title }}@else{{ config('app.name', 'PeaksCurative') }}@endif</title>

    <!-- Tab Icon -->
    <link rel="icon" href="{{ asset('favicon.png') }}">

    <!-- CSS files -->
    <link href="{{ asset('/css/normalize/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('/css/peaks/root.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/peaks/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/peaks/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/peaks/sweetalert-modify.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-pincode-input.css') }}" rel="stylesheet">

    <style>
        :root {
            --peaks-logo: url({{ asset('logo/Logo-white.svg') }});
        }

        body.light-theme {
            --peaks-logo: url({{ asset('logo/Logo-Purple.svg') }});
        }

        .swal2-title {
            font-size: 18px !important;
        }

        .verifyBTN {
            font-size: 12px;
            min-width: auto;
            height: auto;

        }

        .verifytitle {
            font-size: 16px;
            padding: 10px 1px 1px 1px;
            color: #ffffff;
            background-color: #921010bd;
            border-color: #deb88700;
            /* position: absolute; */
            z-index: 999;
            /* left: 0;
            top: 0;
            right: 0; */
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

@if (Auth::user()->phone_verified == 0)

    <div class="alert alert-danger d-flex flex-column justify-content-center align-items-center verifytitle">
        <p class="p-2">
            Phone number not verified. Please verify to use our services.
            <span>
                <button class="btn btn-peaks-hollow verifyBTN" data-bs-toggle="modal" data-bs-target="#VerifyPhone">
                    Verify Now
                </button>
            </span>
        </p>
    </div>

@elseif(Auth::user()->email_verified == 0)

    <div class="alert alert-danger d-flex flex-column justify-content-center align-items-center verifytitle">
        <p class="p-2">Email not verified. Please verify to use our services.
            <span>
                <button data-bs-toggle="modal" data-bs-target="#VerifyEmail" class="btn btn-peaks-hollow verifyBTN">
                    Verify Email
                </button>
            </span>
        </p>
    </div>

@endif

<header>
    <!-- small screens -->
    <nav class="d-md-none navbar-expand-lg nav-container navbar-dark sticky-top">
        <div class="p-3">

            <div class="d-flex justify-content-between">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars ham-menu"></i>
                </button>
                <a href="{{ route('account-home') }}">
                    <div class="peaks-logo"></div>
                </a>
                <div class="theme-toggle">
                    <i id="theme-toggle-mb" class="fas fa-sun change-theme btn-theme"></i>
                </div>
            </div>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <div class="side-bar-links px-3">
                    <a class="side-bar-link {{ Route::current()->getName() == 'account-home' ? 'link-active' : '' }}"
                       href="{{ route('account-home') }}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-home ic-side-bar"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>
                    @if (Auth::check())
                        <a class="side-bar-link {{ Route::current()->getName() == 'account-orders' || Route::current()->getName() == 'order-details' || Route::current()->getName() == 'order.refill-details' ? 'link-active': '' }}"
                           href="{{ route('account-orders') }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shopping-cart ic-side-bar"></i>
                                <span>My Orders</span>
                            </div>
                        </a>
                        <a class="side-bar-link {{ Route::current()->getName() == 'user.plan.index' ? 'link-active' : '' }}"
                           href="{{ route('user.plan.index') }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-sync-alt ic-side-bar"></i>
                                <span>My Plan</span>
                            </div>
                        </a>
                        <a class="side-bar-link {{ Route::current()->getName() == 'account-addresses' ? 'link-active' : '' }}"
                           href="{{ route('account-addresses') }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marked-alt ic-side-bar"></i>
                                <span>My Addresses</span>
                            </div>
                        </a>
                        <a class="side-bar-link {{ Route::current()->getName() == 'account-info' ? 'link-active' : '' }}"
                           href="{{ route('account-info') }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user ic-side-bar"></i>
                                <span>Profile</span>
                            </div>
                        </a>
                    @endif

                    <hr class="bg-secondary my-1"/>

                    <a class="side-bar-link" href="{{ route('user.change-password') }}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-key ic-side-bar"></i>
                            <span>Change Password</span>
                        </div>
                    </a>
                    <a class="side-bar-link" href="{{ route('user.logout') }}">
                        <div class="d-flex align-items-center t-color-red">
                            <i class="fas fa-sign-out-alt ic-side-bar"></i>
                            <span>Logout</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- large screens -->
    <nav class="d-none d-md-flex container-fluid row justify-content-center p-3 mx-0">
        <div class="nav-header d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ route('account-home') }}">
                <div class="peaks-logo"></div>
            </a>

            <div class="d-flex">
                <div class="theme-toggle">
                    <i id="theme-toggle" class="fas fa-sun change-theme"></i>
                </div>
                <div class="dropdown dashboard-dropdown">
                    <a class="nav-link" href="#" type="button" id="my-account-dropdown" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <div class="my-account d-flex align-items-baseline">
                            <i class="fas fa-user ic-svg my-account-icon"></i>
                            <p class="h-color t-bold mb-0">
                                {{ Auth::user()->first_name . ' ' . strtoupper(substr(Auth::user()->last_name, 0, 1)) . '.' }}
                            </p>
                        </div>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="my-account-dropdown">
                        <li>
                            <a class="dropdown-item t-med" href="{{ route('user.change-password') }}">
                                <i class="fas fa-key me-2"></i>Change Password
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item t-med t-color-red" href="{{ route('user.logout') }}">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
{{--                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
{{--                        @csrf--}}
{{--                    </form>--}}
                </div>
            </div>
        </div>
    </nav>
</header>

<main id="app">
    <div class="container-fluid">
        <div class="row justify-content-center px-0 mx-0">
            <div class="col-12 col-lg-11 col-xl-10 px-4 mw-1440px">
                <div class="row">
                    <div class="col-12 col-md-3 vertical-line">
                        <div class="d-flex flex-column">
                            <div class="side-bar-links d-none d-md-block">
                                <a class="side-bar-link {{ Route::current()->getName() == 'account-home' ? 'link-active' : '' }}"
                                   href="{{ route('account-home') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-home ic-side-bar"></i>
                                        <span>Dashboard</span>
                                    </div>
                                </a>
                                @if (Auth::check())
                                    <a class="side-bar-link {{ Route::current()->getName() == 'account-orders' || Route::current()->getName() == 'order-details' || Route::current()->getName() == 'order.refill-details' ? 'link-active': '' }}"
                                       href="{{ route('account-orders') }}">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-shopping-cart ic-side-bar"></i>
                                            <span>My Orders</span>
                                        </div>
                                    </a>
                                    <a class="side-bar-link {{ Route::current()->getName() == 'user.plan.index' ? 'link-active' : '' }}"
                                       href="{{ route('user.plan.index') }}">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-sync-alt ic-side-bar"></i>
                                            <span>My Plan</span>
                                        </div>
                                    </a>
                                    <a class="side-bar-link {{ Route::current()->getName() == 'account-addresses' ? 'link-active' : '' }}"
                                       href="{{ route('account-addresses') }}">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marked-alt ic-side-bar"></i>
                                            <span>My Addresses</span>
                                        </div>
                                    </a>
                                    <a class="side-bar-link {{ Route::current()->getName() == 'account-info' ? 'link-active' : '' }}"
                                       href="{{ route('account-info') }}">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user ic-side-bar"></i>
                                            <span>Profile</span>
                                        </div>
                                    </a>
                                @endif
                            </div>

                            <hr class="bg-secondary d-none d-md-block">

                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="d-md-flex flex-column align-items-center py-4 d-none mt-md-5">
                                        <i class="far fa-question-circle help-icon mb-3"></i>
                                        <p class="text-center">Have any questions or issues?</p>
                                        <a href="{{env('WP_URL')}}/faqs" target="_blank">
                                            <h6 class="t-link">I need help</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9 px-0 px-md-2">
                        @yield('content')
                    </div>
                </div>
                <hr class="bg-secondary d-md-none">
                <div class="d-flex flex-column justify-content-center align-items-center py-4 d-md-none">
                    <i class="far fa-question-circle help-icon mb-3"></i>
                    <p class="text-center">Have any questions or issues?</p>
                    <a href="https://wpdev.peakscurative.com/faqs" target="_blank">
                        <h6 class="t-link">I need help</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('auth.modals.verify-email')
@include('auth.modals.verify-otp')
{{--
<!-- Cookie Policy PopUp -->
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
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/js/parsley.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom JS -->
<script src="{{ asset('js/peaks/main.js') }}"></script>
@if (env('APP_ENV') == 'production')
    <script src="{{ asset('/js/utils/utils.js') }}"></script>
@endif

<script>

    // function submitLogout() {
    //     event.preventDefault();
    //     document.getElementById('logout-form').submit();
    // }
    {{--
    function acceptCookie() {
        $.get('{{ route('save.cookie-agreed') }}', function (data) {
            // cookie saved for 20 years
            $(".cookie_allow").css("display", "none");
        });
    }
    --}}
    $(document).ready(function () {
        @php
            $cookie_agreed = \Cookie::get('cookie_agreed');
        @endphp
        @if ($cookie_agreed != 'yes')
        $(".cookie_allow").css("display", "inline-flex");
        @endif
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
    @if(isset($_REQUEST['error_code']) && $_REQUEST['error_code'] == 2001)
                Swal.fire({
                    title:'{{ Error_2001_Title }}',
                    html: '{{ Error_2001_Description }}',
                    icon: 'error',
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
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

@include('user.base.include-js')

@yield('js')

</body>

</html>
