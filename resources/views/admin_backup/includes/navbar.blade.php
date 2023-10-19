<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{route('admin.home')}}" class="waves-effect">
                        <img src="{{ asset('/images/svg/ic-home.svg') }}" class="icon" height="20">
                        <span>Home</span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <img src="{{ asset('/images/svg/ic-orders.svg') }}" class="icon" height="20">
                        <span> Orders </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.pending.orders')}}">Pending Orders</a></li>
                        <li><a href="{{route('admin.prescribed.orders')}}">Prescribed Orders</a></li>
                        <li><a href="{{route('admin.declined.orders')}}">Declined Orders</a></li>
                        <li><a href="{{route('admin.cancelled.orders')}}">Cancelled Orders</a></li>
{{--                        <li><a href="{{route('admin.past.orders')}}">Past Orders</a></li>--}}
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="{{route('admin.refund.history')}}" class="waves-effect">
                        <img src="{{ asset('/images/svg/ic-card.svg') }}" class="icon" height="20">
                        <span> Refund History </span>
                    </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <img src="{{ asset('/images/svg/ic-settings.svg') }}" class="icon" height="20">
                        <span> Site Settings </span> <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.setting.view') }}">General Setting</a></li>
                        <li><a href="{{ route('admin.setting.plans') }}">Plans Setting</a></li>
                        <li><a href="{{ route('pages.view','privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.view','terms-conditions') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('pages.view','cookie-policy') }}">Cookie Policy</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.subscription.list')}}" class="waves-effect">
                        <img src="{{ asset('/images/svg/ic-card.svg') }}" class="icon" height="20">
                        <span style="padding-left: 4px;">Subscription List</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.subscribers')}}" class="waves-effect">
                        <img src="{{ asset('/images/svg/ic-user.svg') }}" class="icon" height="20">
                        <span style="padding-left: 4px;">Subscribers</span>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
