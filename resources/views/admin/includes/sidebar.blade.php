<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Aside Toolbarl-->
    <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
        <!--begin::Aside user-->
        <!--begin::User
        <div class="aside-user d-flex align-items-sm-center justify-content-center py-5">

            <div class="symbol symbol-50px">
                <img src="{{asset('/images/png/profile.png')}}" alt="" />
            </div>

            <div class="aside-user-info flex-row-fluid flex-wrap ms-5">

                <div class="d-flex">

                    <div class="flex-grow-1 me-2">

                        <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</a>

                        <div class="d-flex align-items-center text-success fs-9">
                        <span class="bullet bullet-dot bg-success me-1"></span>online</div>

                    </div>

                </div>

            </div>

        </div>
         end::User-->

        <!--end::Aside user-->
    </div>
    <!--end::Aside Toolbarl-->

    @php
    //Getting Current URL to Match route;
        $currentURL = URL::current();
    @endphp

    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y px-2 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">

                <div class="menu-item  ">
                    <a class="menu-link {{$currentURL==route('admin.home')?'active':''}}" href="{{route('admin.home')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                @if((in_array('admin.admin-user.list',$permissions) || in_array('admin.permission.list',$permissions) || in_array('admin.role.list',$permissions)) || Auth::user()->u_type=='superadmin') 

                @if(($currentURL==route('admin.admin-user.list') || $currentURL==route('admin.permission.list') || $currentURL==route('admin.role.list') || $currentURL==route('admin.role.add') || $currentURL==route('admin.role.show') || request()->is('admin/role/*'))) 
                    <div data-kt-menu-trigger="click" class="menu-item  menu-accordion hover show">
                @else
                    <div data-kt-menu-trigger="click" class="menu-item  menu-accordion">
                @endif
                
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm001.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">User Management</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(in_array('admin.admin-user.list',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{$currentURL==route('admin.admin-user.list')?'active':''}}" href="{{route('admin.admin-user.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Users List</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.permission.list',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{$currentURL==route('admin.permission.list')?'active':''}}" href="{{route('admin.permission.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">User Permissions</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.prescribed.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{(request()->is('admin/role/*') || request()->is('admin/role'))?'active':''}}" href="{{route('admin.role.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">User Roles</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="menu-item">
                    <div class="menu-content pt-8 pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Apps</span>
                    </div>
                </div>

                @if((in_array('admin.daily.orders',$permissions) || in_array('admin.pending.orders',$permissions) || in_array('admin.prescribed.orders',$permissions) || in_array('admin.declined.orders',$permissions) || in_array('admin.cancelled.orders',$permissions) || in_array('admin.expired.orders',$permissions) || in_array('admin.unship.orders',$permissions)) ||
                in_array('admin.beluga.cancellation.pending.orders',$permissions) || 
                Auth::user()->u_type=='superadmin') 
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm001.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M18.041 22.041C18.5932 22.041 19.041 21.5932 19.041 21.041C19.041 20.4887 18.5932 20.041 18.041 20.041C17.4887 20.041 17.041 20.4887 17.041 21.041C17.041 21.5932 17.4887 22.041 18.041 22.041Z" fill="black" />
                                    <path opacity="0.3" d="M6.04095 22.041C6.59324 22.041 7.04095 21.5932 7.04095 21.041C7.04095 20.4887 6.59324 20.041 6.04095 20.041C5.48867 20.041 5.04095 20.4887 5.04095 21.041C5.04095 21.5932 5.48867 22.041 6.04095 22.041Z" fill="black" />
                                    <path opacity="0.3" d="M7.04095 16.041L19.1409 15.1409C19.7409 15.1409 20.141 14.7409 20.341 14.1409L21.7409 8.34094C21.9409 7.64094 21.4409 7.04095 20.7409 7.04095H5.44095L7.04095 16.041Z" fill="black" />
                                    <path d="M19.041 20.041H5.04096C4.74096 20.041 4.34095 19.841 4.14095 19.541C3.94095 19.241 3.94095 18.841 4.14095 18.541L6.04096 14.841L4.14095 4.64095L2.54096 3.84096C2.04096 3.64096 1.84095 3.04097 2.14095 2.54097C2.34095 2.04097 2.94096 1.84095 3.44096 2.14095L5.44096 3.14095C5.74096 3.24095 5.94096 3.54096 5.94096 3.84096L7.94096 14.841C7.94096 15.041 7.94095 15.241 7.84095 15.441L6.54096 18.041H19.041C19.641 18.041 20.041 18.441 20.041 19.041C20.041 19.641 19.641 20.041 19.041 20.041Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Order</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(in_array('admin.daily.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link daily-orders {{$currentURL==route('admin.daily.orders')?'active':''}} " href="{{route('admin.daily.orders')}}" >
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Daily Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.pending.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link pending-orders {{$currentURL==route('admin.pending.orders')?'active':''}} " href="{{route('admin.pending.orders')}}" >
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Pending Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.prescribed.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link prescribed-orders {{$currentURL==route('admin.prescribed.orders')?'active':''}}" href="{{route('admin.prescribed.orders')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Prescribed Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.declined.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link declined-orders {{$currentURL==route('admin.declined.orders')?'active':''}}" href="{{route('admin.declined.orders')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Declined Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.beluga.cancellation.pending.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link beluga-cancellation-pending-order {{$currentURL==route('admin.beluga.cancellation.pending.orders')?'active':''}}" href="{{route('admin.beluga.cancellation.pending.orders')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Beluga Cancellation Pending Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.cancelled.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link cancelled-orders {{$currentURL==route('admin.cancelled.orders')?'active':''}}" href="{{route('admin.cancelled.orders')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Cancelled Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.expired.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link expired-orders {{$currentURL==route('admin.expired.orders')?'active':''}} " href="{{route('admin.expired.orders')}}" >
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Expired Orders</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.unship.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link unshipped-orders {{$currentURL==route('admin.unship.orders')?'active':''}} " href="{{route('admin.unship.orders')}}" >
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Unshipped Orders</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @if(in_array('admin.failed.refill.transaction',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link {{($currentURL==route('admin.failed.refill.transaction') || request()->segment(2) == 'view-failed-refill-details')?'active':''}}" href="{{route('admin.failed.refill.transaction')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor"/>
                                    <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor"/>
                                </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Failed Refill Transactions</span>
                    </a>
                </div>
                @endif
                @if(in_array('admin.beluga.pending.orders',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link {{($currentURL==route('admin.beluga.pending.orders'))?'active':''}}" href="{{route('admin.beluga.pending.orders')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                    <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                                    <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                                    </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Beluga Pending Orders</span>
                    </a>
                </div>
                @endif
                @if(in_array('admin.customers.list',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link customers-list {{($currentURL==route('admin.customers.list') || request()->segment(3) == 'customers-view')?'active':''}}" href="{{route('admin.customers.list')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black"/>
                                    <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black"/>
                                    </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Customers</span>
                    </a>
                </div>
                @endif
                @if(in_array('admin.promo.code',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link promo-code {{$currentURL==route('admin.promo.code')?'active':''}}" href="{{route('admin.promo.code')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21.6 11.2L19.3 8.89998V5.59993C19.3 4.99993 18.9 4.59993 18.3 4.59993H14.9L12.6 2.3C12.2 1.9 11.6 1.9 11.2 2.3L8.9 4.59993H5.6C5 4.59993 4.6 4.99993 4.6 5.59993V8.89998L2.3 11.2C1.9 11.6 1.9 12.1999 2.3 12.5999L4.6 14.9V18.2C4.6 18.8 5 19.2 5.6 19.2H8.9L11.2 21.5C11.6 21.9 12.2 21.9 12.6 21.5L14.9 19.2H18.2C18.8 19.2 19.2 18.8 19.2 18.2V14.9L21.5 12.5999C22 12.1999 22 11.6 21.6 11.2Z" fill="currentColor"/>
                                    <path d="M11.3 9.40002C11.3 10.2 11.1 10.9 10.7 11.3C10.3 11.7 9.8 11.9 9.2 11.9C8.8 11.9 8.40001 11.8 8.10001 11.6C7.80001 11.4 7.50001 11.2 7.40001 10.8C7.20001 10.4 7.10001 10 7.10001 9.40002C7.10001 8.80002 7.20001 8.4 7.30001 8C7.40001 7.6 7.7 7.29998 8 7.09998C8.3 6.89998 8.7 6.80005 9.2 6.80005C9.5 6.80005 9.80001 6.9 10.1 7C10.4 7.1 10.6 7.3 10.8 7.5C11 7.7 11.1 8.00005 11.2 8.30005C11.3 8.60005 11.3 9.00002 11.3 9.40002ZM10.1 9.40002C10.1 8.80002 10 8.39998 9.90001 8.09998C9.80001 7.79998 9.6 7.70007 9.2 7.70007C9 7.70007 8.8 7.80002 8.7 7.90002C8.6 8.00002 8.50001 8.2 8.40001 8.5C8.40001 8.7 8.30001 9.10002 8.30001 9.40002C8.30001 9.80002 8.30001 10.1 8.40001 10.4C8.40001 10.6 8.5 10.8 8.7 11C8.8 11.1 9 11.2001 9.2 11.2001C9.5 11.2001 9.70001 11.1 9.90001 10.8C10 10.4 10.1 10 10.1 9.40002ZM14.9 7.80005L9.40001 16.7001C9.30001 16.9001 9.10001 17.1 8.90001 17.1C8.80001 17.1 8.70001 17.1 8.60001 17C8.50001 16.9 8.40001 16.8001 8.40001 16.7001C8.40001 16.6001 8.4 16.5 8.5 16.4L14 7.5C14.1 7.3 14.2 7.19998 14.3 7.09998C14.4 6.99998 14.5 7 14.6 7C14.7 7 14.8 6.99998 14.9 7.09998C15 7.19998 15 7.30002 15 7.40002C15.2 7.30002 15.1 7.50005 14.9 7.80005ZM16.6 14.2001C16.6 15.0001 16.4 15.7 16 16.1C15.6 16.5 15.1 16.7001 14.5 16.7001C14.1 16.7001 13.7 16.6 13.4 16.4C13.1 16.2 12.8 16 12.7 15.6C12.5 15.2 12.4 14.8001 12.4 14.2001C12.4 13.3001 12.6 12.7 12.9 12.3C13.2 11.9 13.7 11.7001 14.5 11.7001C14.8 11.7001 15.1 11.8 15.4 11.9C15.7 12 15.9 12.2 16.1 12.4C16.3 12.6 16.4 12.9001 16.5 13.2001C16.6 13.4001 16.6 13.8001 16.6 14.2001ZM15.4 14.1C15.4 13.5 15.3 13.1 15.2 12.9C15.1 12.6 14.9 12.5 14.5 12.5C14.3 12.5 14.1 12.6001 14 12.7001C13.9 12.8001 13.8 13.0001 13.7 13.2001C13.6 13.4001 13.6 13.8 13.6 14.1C13.6 14.7 13.7 15.1 13.8 15.4C13.9 15.7 14.1 15.8 14.5 15.8C14.8 15.8 15 15.7 15.2 15.4C15.3 15.2 15.4 14.7 15.4 14.1Z" fill="currentColor"/>
                                    </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Promo Codes</span>
                    </a>
                </div>
                @endif
                @if(in_array('admin.refund.history',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link refund-history {{$currentURL==route('admin.refund.history')?'active':''}}" href="{{route('admin.refund.history')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M21.6 11.2L19.3 8.89998V5.59993C19.3 4.99993 18.9 4.59993 18.3 4.59993H14.9L12.6 2.3C12.2 1.9 11.6 1.9 11.2 2.3L8.9 4.59993H5.6C5 4.59993 4.6 4.99993 4.6 5.59993V8.89998L2.3 11.2C1.9 11.6 1.9 12.1999 2.3 12.5999L4.6 14.9V18.2C4.6 18.8 5 19.2 5.6 19.2H8.9L11.2 21.5C11.6 21.9 12.2 21.9 12.6 21.5L14.9 19.2H18.2C18.8 19.2 19.2 18.8 19.2 18.2V14.9L21.5 12.5999C22 12.1999 22 11.6 21.6 11.2Z" fill="black"/>
                                    <path d="M11.3 9.40002C11.3 10.2 11.1 10.9 10.7 11.3C10.3 11.7 9.8 11.9 9.2 11.9C8.8 11.9 8.40001 11.8 8.10001 11.6C7.80001 11.4 7.50001 11.2 7.40001 10.8C7.20001 10.4 7.10001 10 7.10001 9.40002C7.10001 8.80002 7.20001 8.4 7.30001 8C7.40001 7.6 7.7 7.29998 8 7.09998C8.3 6.89998 8.7 6.80005 9.2 6.80005C9.5 6.80005 9.80001 6.9 10.1 7C10.4 7.1 10.6 7.3 10.8 7.5C11 7.7 11.1 8.00005 11.2 8.30005C11.3 8.60005 11.3 9.00002 11.3 9.40002ZM10.1 9.40002C10.1 8.80002 10 8.39998 9.90001 8.09998C9.80001 7.79998 9.6 7.70007 9.2 7.70007C9 7.70007 8.8 7.80002 8.7 7.90002C8.6 8.00002 8.50001 8.2 8.40001 8.5C8.40001 8.7 8.30001 9.10002 8.30001 9.40002C8.30001 9.80002 8.30001 10.1 8.40001 10.4C8.40001 10.6 8.5 10.8 8.7 11C8.8 11.1 9 11.2001 9.2 11.2001C9.5 11.2001 9.70001 11.1 9.90001 10.8C10 10.4 10.1 10 10.1 9.40002ZM14.9 7.80005L9.40001 16.7001C9.30001 16.9001 9.10001 17.1 8.90001 17.1C8.80001 17.1 8.70001 17.1 8.60001 17C8.50001 16.9 8.40001 16.8001 8.40001 16.7001C8.40001 16.6001 8.4 16.5 8.5 16.4L14 7.5C14.1 7.3 14.2 7.19998 14.3 7.09998C14.4 6.99998 14.5 7 14.6 7C14.7 7 14.8 6.99998 14.9 7.09998C15 7.19998 15 7.30002 15 7.40002C15.2 7.30002 15.1 7.50005 14.9 7.80005ZM16.6 14.2001C16.6 15.0001 16.4 15.7 16 16.1C15.6 16.5 15.1 16.7001 14.5 16.7001C14.1 16.7001 13.7 16.6 13.4 16.4C13.1 16.2 12.8 16 12.7 15.6C12.5 15.2 12.4 14.8001 12.4 14.2001C12.4 13.3001 12.6 12.7 12.9 12.3C13.2 11.9 13.7 11.7001 14.5 11.7001C14.8 11.7001 15.1 11.8 15.4 11.9C15.7 12 15.9 12.2 16.1 12.4C16.3 12.6 16.4 12.9001 16.5 13.2001C16.6 13.4001 16.6 13.8001 16.6 14.2001ZM15.4 14.1C15.4 13.5 15.3 13.1 15.2 12.9C15.1 12.6 14.9 12.5 14.5 12.5C14.3 12.5 14.1 12.6001 14 12.7001C13.9 12.8001 13.8 13.0001 13.7 13.2001C13.6 13.4001 13.6 13.8 13.6 14.1C13.6 14.7 13.7 15.1 13.8 15.4C13.9 15.7 14.1 15.8 14.5 15.8C14.8 15.8 15 15.7 15.2 15.4C15.3 15.2 15.4 14.7 15.4 14.1Z" fill="black"/>
                                    </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Refund History</span>
                    </a>
                </div>
                @endif
                @if((in_array('admin.plan.type.list',$permissions) || in_array('admin.medicine.list',$permissions) || in_array('admin.products.list',$permissions)) || $currentURL==route('admin.plan.list') || Auth::user()->u_type=='superadmin') 

                @if(($currentURL==route('admin.products.list') || $currentURL==route('admin.plan.type.list') || $currentURL==route('admin.medicine.list') || $currentURL==route('admin.plan.list') || request()->is('admin/plan/*'))) 
                    <div data-kt-menu-trigger="click" class="menu-item  menu-accordion hover show">
                @else
                    <div data-kt-menu-trigger="click" class="menu-item  menu-accordion">
                @endif
                
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm001.svg-->
                            <span class="svg-icon svg-icon-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z" fill="currentColor"></path>
                                                        <path d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z" fill="currentColor"></path>
                                                        <path opacity="0.3" d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z" fill="currentColor"></path>
                                                        <path opacity="0.3" d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Plan Management</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(in_array('admin.products.list',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{$currentURL==route('admin.products.list')?'active':''}}" href="{{route('admin.products.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Product List</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.plan.type.list',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{$currentURL==route('admin.plan.type.list')?'active':''}}" href="{{route('admin.plan.type.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Plan Types</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.medicine.list',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{$currentURL==route('admin.medicine.list')?'active':''}}" href="{{route('admin.medicine.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Medicine Variants</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.plan.list',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link {{($currentURL==route('admin.plan.list') || request()->is('admin/plan/*'))?'active':''}}" href="{{route('admin.plan.list')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">List of Plans</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                {{--@if(in_array('admin.refund.failed',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link refund-failed {{$currentURL==route('admin.refund.failed')?'active':''}}" href="{{route('admin.refund.failed')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                    <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                                    <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                                    </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Refund Failed</span>
                    </a>
                </div>
                @endif --}}

                @php
                    $menuDropSubscription = false;
                    $subscriptionsLinks = [route('admin.subscriptions.active'), route('admin.subscriptions.paused'), route('admin.subscriptions.expired'), route('admin.subscriptions.cancelled')];
                    if (in_array(URL::current(), $subscriptionsLinks)) {
                        $menuDropSubscription = true;
                    }
                @endphp
                @if((in_array('admin.subscriptions.active',$permissions) || in_array('admin.subscriptions.paused',$permissions) || in_array('admin.subscriptions.expired',$permissions) || in_array('admin.subscriptions.cancelled',$permissions)) || Auth::user()->u_type=='superadmin') 
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1 {{ $menuDropSubscription ? 'show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z" fill="black"/>
                                    <path opacity="0.3" d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z" fill="black"/>
                                    </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Subscription Plans</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(in_array('admin.subscriptions.active',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link active-subscriptions {{ $currentURL==route('admin.subscriptions.active') ? 'active' : '' }}"
                               href="{{ route('admin.subscriptions.active') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Active Subscriptions</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.subscriptions.paused',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link paused-subscriptions {{ $currentURL==route('admin.subscriptions.paused') ? 'active' : '' }}"
                               href="{{ route('admin.subscriptions.paused') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Paused Subscriptions</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.subscriptions.expired',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link expired-subscriptions {{ $currentURL==route('admin.subscriptions.expired') ? 'active' : '' }}"
                               href="{{ route('admin.subscriptions.expired') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Expired Subscriptions</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.subscriptions.cancelled',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link cancelled-subscriptions {{ $currentURL==route('admin.subscriptions.cancelled') ? 'active' : '' }}"
                               href="{{ route('admin.subscriptions.cancelled') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Cancelled Subscriptions</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if((in_array('admin.setting.view',$permissions) || in_array('admin.shipping.view',$permissions) || in_array('admin.setting.plans',$permissions) || in_array('admin.pages.view',$permissions)) || Auth::user()->u_type=='superadmin')
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen051.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="black" />
                                    <path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Site Settings</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(in_array('admin.setting.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link general-setting {{$currentURL==route('admin.setting.view')?'active':''}}" href="{{ route('admin.setting.view') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">General Setting</span>
                            </a>
                        </div>
                        @endif

                        {{-- @if(in_array('admin.shiping.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link shipping-setting {{$currentURL==route('admin.shipping.view')?'active':''}}" href="{{ route('admin.shipping.view') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Shipping Setting</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.setting.plans',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            @php
                                $currentPlace = explode('/',$currentURL);
                            @endphp

                            <a class="menu-link plans-setting {{$currentURL==route('admin.setting.plans') || $currentPlace[4]=='plans-setting'?'active':''}}" href="{{ route('admin.setting.plans') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Plans Setting</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.pages.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link privacy-policy {{$currentURL==route('pages.view','privacy-policy')?'active':''}}" href="{{ route('pages.view','privacy-policy') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Privacy Policy</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.pages.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link terms-conditions {{$currentURL==route('pages.view','terms-conditions')?'active':''}}" href="{{ route('pages.view','terms-conditions') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Terms & Conditions</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.pages.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link cookie-policy {{$currentURL==route('pages.view','cookie-policy')?'active':''}}" href="{{ route('pages.view','cookie-policy') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Cookie Policy</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.pages.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link telehealth-consent {{$currentURL==route('pages.view','telehealth-consent')?'active':''}}" href="{{ route('pages.view','telehealth-consent') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Telehealth Consent</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.pages.view',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link refund-policy {{$currentURL==route('pages.view','refund-policy')?'active':''}}" href="{{ route('pages.view','refund-policy') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Refund Policy</span>
                            </a>
                        </div>
                        @endif
                        --}}
                        @if(in_array('admin.beluga.setting',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link beluga-setting {{$currentURL==route('admin.beluga.setting')?'active':''}}" href="{{ route('admin.beluga.setting') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Beluga Setting</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                {{--
                @if((in_array('admin.subscribers',$permissions) || in_array('admin.contact-us-data',$permissions)) || Auth::user()->u_type=='superadmin') 
                <div data-kt-menu-trigger="click" class="menu-item   menu-accordion mb-1">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black"/>
                                    <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black"/>
                                    </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Subscribers</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(in_array('admin.subscribers',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link all-subscribers {{ $currentURL==route('admin.subscribers')?'active':'' }}" href="{{ route('admin.subscribers') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Pop Up Data</span>
                            </a>
                        </div>
                        @endif
                        @if(in_array('admin.contact-us-data',$permissions) || Auth::user()->u_type=='superadmin') 
                        <div class="menu-item">
                            <a class="menu-link contact-us {{ $currentURL==route('admin.contact-us-data')?'active':'' }}" href="{{ route('admin.contact-us-data') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Contact Us Data</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                --}}
                @if(in_array('admin.peaks.errors',$permissions) || Auth::user()->u_type=='superadmin') 
                <div class="menu-item">
                    <a class="menu-link peaks-error-codes {{$currentURL==route('admin.peaks.errors')?'active':''}}" href="{{route('admin.peaks.errors')}}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                    <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                                    <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                                    </svg>
                            </span>

                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Error Codes</span>
                    </a>
                </div>
                @endif

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto py-5" id="kt_aside_footer">

    </div>
    <!--end::Footer-->
</div>
