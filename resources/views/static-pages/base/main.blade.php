<!doctype html>
<html lang="en">
<head>
    <title>@if($site_setting && $site_setting->site_title){{$site_setting->site_title}}@else{{ config('app.name', 'PeaksCurative') }}@endif | @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

    <meta name="keywords"
          content="Sexual Health, Erectile Dysfunction, Impotence, Sexual Dysfunction, Sildenafil, Tadalafil, Men's healthcare, Everyday Healthcare, Mental Health, Premature ejaculation, Online Pharmacy, ROMAN Testostorone Support, ED treatment, ED pills, Erectile Dysfunction pill, ED medicine, Supplements, Sildenafil side effects, Cialis side effects, Side effects Tadalafil, Erectile Dysfunction Treatments, Over the counter pills for ED, Erectile pills, Men's Wellness, Sexual Perfomance, Erectile dysfunction causes, Erection problem, Telemedicine, Telehealth">
    <meta name="description"
          content="@yield('meta_description', 'Sexual health and performance. Get a tasty, safe and effective Erectile Dysfunction ED treatment with our gummies. Real prescription medication online')">

    <link rel="canonical" href="{{ url()->current() }}"/>

    <!-- Tab Icon -->
    <link rel="icon" href="{{ asset('favicon.png') }}">

    <!-- CSS files -->
    <link href="{{ asset('/css/normalize/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('/css/peaks/root.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/peaks/styles.css') }}" rel="stylesheet">

    <style>
        :root {
            --peaks-logo: url({{ asset('logo/Logo-white.svg') }});
        }
        body.light-theme
        {
            --peaks-logo: url({{ asset('logo/Logo-Purple.svg') }});
        }
    </style>

    @yield('css')


    <!-- Head section JS -->

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T6VGR4F');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DNVNK61DV7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-DNVNK61DV7');
    </script>
    <!-- End: Google Analytics - G -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-214302187-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-214302187-1');
    </script>
    <!-- End: Google Analytics - UA-->

</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T6VGR4F"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->


@include('static-pages.base.components.navbar')

<main id="front">
    @yield('content')
</main>
{{--
<!-- Cookie Policy PopUp -->
<div class="banner cookie_allow" style="display: none;">
    <div class="cookie-policy-banner flex-column flex-md-row gap-3">
        <p class="mb-0 me-3">
            This website uses cookies to ensure you get the best experience on our website PeaksCurative
            <a href="{{ route('cookie-policy') }}" target="_blank">Cookie Policy.</a>
        </p>
        <button class="btn btn-peaks-hollow btn-small" onclick="acceptCookie()">Allow</button>
    </div>
</div>
--}}
<div class="loaderElement blockui-overlay" style="display: none;">
    <div class="blockui-message">
      <i class="fas fa-circle-notch fa-spin" style="font-size: 22px;margin-right: 8px;"></i> Please Wait
    </div>

</div>
@include('static-pages.base.components.footer')

<!-- JS files -->
<script src="{{ asset('/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script src="{{ asset('js/peaks/main.js') }}"></script>
@if(env('APP_ENV') == 'production')
    <script src="{{ asset('/js/utils/utils.js') }}"></script>
@endif

<script>
    {{--
    function acceptCookie() {
        $.get('{{ route('save.cookie-agreed') }}', function(data) {
            $(".cookie_allow").css("display","none");
        });
    }
    --}}
    $(document).ready(function() {
        @php
            $cookie_agreed = \Cookie::get('cookie_agreed');
        @endphp

        @if($cookie_agreed != 'yes')
            $(".cookie_allow").css("display","inline-flex");
        @endif
    });
</script>

@yield('js')

</body>
</html>
