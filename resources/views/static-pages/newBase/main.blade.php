<!DOCTYPE html>
<html lang="en">
<head>
    <title>@if($site_setting && $site_setting->site_title){{$site_setting->site_title}}@else{{ config('app.name', 'PeaksCurative') }}@endif | @yield('title')</title>

	<meta content='width=device-width, initial-scale=1.0, user-scalable=1' name='viewport'/>
    <meta name="description" content="@yield('meta_description', 'Sexual health and performance. Get a tasty, safe and effective Erectile Dysfunction ED treatment with our gummies. Real prescription medication online')">

    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

    <link rel="icon" href="{{ asset('favicon.png') }}">

    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/home.css') }}">
    <link href="{{ asset('intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">

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

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T6VGR4F');
    </script>
    <!-- End Google Tag Manager -->

</head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T6VGR4F" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

@yield('header')

@yield('content')
{{--
<!-- begin:Cookie Policy PopUp -->
<!-- <div class="banner cookie_allow" style="display: none;">
    <div class="cookie-policy-banner flex-column flex-md-row gap-3">
        <p class="mb-0 mx-3">
            This website uses cookies to ensure you get the best experience on our website PeaksCurative
            <a href="{{ route('cookie-policy') }}" target="_blank">Cookie Policy.</a>
        </p>
        <button class="newBtn btn-peaks-hollow btn-small" onclick="acceptCookie()">Allow</button>
    </div>
</div> -->
<!-- end:Cookie Policy PopUp -->
--}}
<div class="loaderElement blockui-overlay" style="display: none;">
    <div class="blockui-message">
      <i class="fas fa-circle-notch fa-spin" style="font-size: 22px;margin-right: 8px;"></i> Please Wait
    </div>
</div>


@include('static-pages.newBase.components.footer')


<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="{{ asset('/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/peaks/main.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    $(function() {
	$('.card-header').on('click', function(){
		if($(this).hasClass('active-acc')){
			$(this).removeClass('active-acc');
		} else {
			$('.card-header').removeClass('active-acc');
			$(this).addClass('active-acc');
		}
	});

    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

	var sun_moon_img = [ "assets/sun.svg", "assets/moon.svg" ];

	$('.modes-btn').click(function() {
		this.src = sun_moon_img[ this.src.match('sun.svg') ? 1 : 0 ];
	    $('body').toggleClass('light');
        if ($("body").hasClass("light")) {
        } else {
        }
	});

    const selectedTheme = localStorage.getItem('selected-theme');
    if (selectedTheme === 'light') {
        $('.modes-btn').attr( "src", "assets/moon.svg");
        $('body').toggleClass('light');
    }

});
</script>

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
