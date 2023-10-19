<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="">
        <title>@yield('title') | @if($site_setting && $site_setting->site_title){{$site_setting->site_title}}@else{{ config('app.name', 'PeaksCurative') }}@endif</title>
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
		<!--begin::Page Vendor Stylesheets(used by this page)-->
		<link href="{{asset('/admin_assets/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/admin_assets/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('/css/peaks/order-tracker.css') }}" rel="stylesheet">
		<!--end::Page Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->

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
				.ck-editor__editable {
					 min-height: 250px !important;
					 overflow: scroll;
				}
				.ck-editor__editable_inline {
					min-height: 250px !important;
					overflow: scroll;
				}
			</style>

			@endif
		<!--end::Global Stylesheets Bundle-->

        @yield('css')
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled  @if (isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] == '1') dark-mode @endif">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="loaderElement blockui-overlay" style="display: none; z-index: 999;position: fixed">
			<div class="blockui-message">
				<i class="fas fa-circle-notch fa-spin  text-primary" style="font-size: 22px;margin-right: 8px;"></i> Please Wait
			</div>
		</div>
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->


            @yield('login-content')
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
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Vendors Javascript(used by this page)-->
		<script src="{{asset('/admin_assets/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
		<script src="{{asset('/admin_assets/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
		<!--end::Page Vendors Javascript-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="{{asset('/admin_assets/assets/js/widgets.bundle.js')}}"></script>

		<script src="{{asset('/admin_assets/assets/js/custom/apps/chat/chat.js')}}"></script>
		<script src="{{asset('/admin_assets/assets/js/custom/utilities/modals/users-search.js')}}"></script>

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
		    <script>
				@php
				$currentPlace = explode('/',URL::current());
				$urlName = explode('?',$currentPlace[4]);
				if ($urlName[0] == 'page-content') {
					$urlName = explode('?',$currentPlace[5]);
				}
				@endphp
				$('.{{$urlName[0]}}').parent().parent().parent().addClass("show here")

			</script>
        @yield('js')
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
