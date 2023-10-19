<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <a href="{{route('admin.home')}}" class="logo">
                    <i class="icon-c-logo"><img src="@if($site_setting && $site_setting->site_logo_light){{getImage($site_setting->site_logo_light)}}@else{{asset('images/svg/peaks-logo-dark.svg')}}@endif" height="27"/> </i>
                    <span><img src="@if($site_setting && $site_setting->site_logo_light){{getImage($site_setting->site_logo_light)}}@else{{asset('images/svg/peaks-logo-dark.svg')}}@endif" height="46" style="width:107px;height:56px;" /></span>
            </a>
        </div>
    </div>
    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="md md-menu"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>
                <div class="pull-left" style="margin-top: 13px;font-size: 22px;color: #fff;">
                    <span>Admin</span>
                </div>
                <ul class="nav navbar-nav navbar-right pull-right">
                    <li class="hidden-xs">
                        <a href="javascript:void(0);" style="margin-right: -12px;">
                            {{Auth::user()->first_name.' '.Auth::user()->last_name}}
                        </a>
                    </li>
                    <li class="dropdown top-menu-item-xs">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light"
                           data-toggle="dropdown" aria-expanded="true">
                            <img src="{{asset('/images/png/profile.png')}}" alt="user-img" class="img-circle">
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{url('/admin/change-password')}}">
                                    <i class="ti-user m-r-10 text-custom"></i>Change password
                                </a>
                            </li>
                            <li onclick="launchLogout()"><a href="javascript:void(0)"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        function submitLogout() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    </script>
@endsection
