@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Dashboard')

@section('navbar')
    @include('admin.includes.sidebar')
@endsection

@section('header')
    @include('admin.includes.header')
@endsection

@section('css')
<style>
    .total-widget {
        font-size: 25px
    }

</style>
@endsection
@section('content')

    <div class="row">
        <div class="col-xl-4">
            <!--begin::Mixed Widget 2-->
            <div class=" card-xl-stretch mb-xl-8">
                <!--begin::Body-->
                <div class="card-body  p-0">

                    <!--begin::Stats-->
                    <div class="card-p  rounded-2">
                        <!--begin::Row-->
                        <div class="row g-0">
                            <!--begin::Col-->
                            <div class="col bg-light-warning p-3 text-center rounded-2 me-7 mb-7">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                                <a href="{{ route('admin.pending.orders', ['today' => date('Y-m-d')]) }}">
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <rect x="8" y="9" width="3" height="10" rx="1.5" fill="black"></rect>
                                            <rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black">
                                            </rect>
                                            <rect x="18" y="11" width="3" height="8" rx="1.5" fill="black"></rect>
                                            <rect x="3" y="13" width="3" height="6" rx="1.5" fill="black"></rect>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <p> <span class="badge badge-warning">{{ $totalTodayPendingOrders }}</span></p>
                                    <p class="text-warning fw-bold fs-7">Today's Pending Orders</p>
                                </a>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col bg-light-success p-3 text-center rounded-2 mb-7">
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <a href="{{ route('admin.prescribed.orders', ['today' => date('Y-m-d')]) }}">
                                    <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path opacity="0.3"
                                                d="M18.041 22.041C18.5932 22.041 19.041 21.5932 19.041 21.041C19.041 20.4887 18.5932 20.041 18.041 20.041C17.4887 20.041 17.041 20.4887 17.041 21.041C17.041 21.5932 17.4887 22.041 18.041 22.041Z"
                                                fill="black" />
                                            <path opacity="0.3"
                                                d="M6.04095 22.041C6.59324 22.041 7.04095 21.5932 7.04095 21.041C7.04095 20.4887 6.59324 20.041 6.04095 20.041C5.48867 20.041 5.04095 20.4887 5.04095 21.041C5.04095 21.5932 5.48867 22.041 6.04095 22.041Z"
                                                fill="black" />
                                            <path opacity="0.3"
                                                d="M7.04095 16.041L19.1409 15.1409C19.7409 15.1409 20.141 14.7409 20.341 14.1409L21.7409 8.34094C21.9409 7.64094 21.4409 7.04095 20.7409 7.04095H5.44095L7.04095 16.041Z"
                                                fill="black" />
                                            <path
                                                d="M19.041 20.041H5.04096C4.74096 20.041 4.34095 19.841 4.14095 19.541C3.94095 19.241 3.94095 18.841 4.14095 18.541L6.04096 14.841L4.14095 4.64095L2.54096 3.84096C2.04096 3.64096 1.84095 3.04097 2.14095 2.54097C2.34095 2.04097 2.94096 1.84095 3.44096 2.14095L5.44096 3.14095C5.74096 3.24095 5.94096 3.54096 5.94096 3.84096L7.94096 14.841C7.94096 15.041 7.94095 15.241 7.84095 15.441L6.54096 18.041H19.041C19.641 18.041 20.041 18.441 20.041 19.041C20.041 19.641 19.641 20.041 19.041 20.041Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <p> <span class="badge badge-success">{{ $totalTodayPrescribedOrders }}</span></p>
                                    <p class="text-success fw-bold fs-7">Today's Prescribed Orders</p>
                                </a>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row g-0">
                            <!--begin::Col-->
                            <div class="col bg-light-danger p-3 text-center rounded-2 me-7">
                                <!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
                                <a href="{{ route('admin.declined.orders', ['today' => date('Y-m-d')]) }}">
                                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path opacity="0.3"
                                                d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
                                                fill="black"></path>
                                            <path
                                                d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <p> <span class="badge badge-danger">{{ $totalTodayDeclinedOrders }}</span></p>
                                    <p class="text-danger fw-bold fs-7 mt-2">Today's Declined Orders</p>
                                </a>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col bg-danger p-3 text-center rounded-2">
                                <!--begin::Svg Icon | path: icons/duotune/communication/com010.svg-->
                                <a href="{{ route('admin.cancelled.orders', ['today' => date('Y-m-d')]) }}">
                                    <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path opacity="0.3"
                                                d="M10.9607 12.9128H18.8607C19.4607 12.9128 19.9607 13.4128 19.8607 14.0128C19.2607 19.0128 14.4607 22.7128 9.26068 21.7128C5.66068 21.0128 2.86071 18.2128 2.16071 14.6128C1.16071 9.31284 4.96069 4.61281 9.86069 4.01281C10.4607 3.91281 10.9607 4.41281 10.9607 5.01281V12.9128Z"
                                                fill="black"></path>
                                            <path
                                                d="M12.9607 10.9128V3.01281C12.9607 2.41281 13.4607 1.91281 14.0607 2.01281C16.0607 2.21281 17.8607 3.11284 19.2607 4.61284C20.6607 6.01284 21.5607 7.91285 21.8607 9.81285C21.9607 10.4129 21.4607 10.9128 20.8607 10.9128H12.9607Z"
                                                fill="black"></path>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <p> <span class="badge badge-light-danger">{{ $totalTodayCancelledOrders }}</span></p>
                                    <p class="text-white fw-bold fs-7 mt-2">Today's Cancelled Orders</p>
                                </a>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 2-->
        </div>
     
        <div class="row col">
            <div class="col-xl-6 mb-2">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('admin.daily.orders') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 ">
                    <!--begin::Body-->
                    <div class="card-body row text-center">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                        <span class="svg-icon svg-icon-primary svg-icon-3x ms-n1 col-3 text-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor"/>
                                <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor"/>
                                <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor"/>
                                <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor"/>
                                </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="col text-center text-gray-900 fw-bolder fs-5 mt-4">Total Daily Orders</div>
                        <div class="col-3 text-end">
                            <div class="text-gray-900 fw-bolder fs-2"> <span
                                    class="total-widget badge badge-light-primary">{{ $totalDailyOrders ? $totalDailyOrders : 0 }}</span>
                            </div>

                        </div>

                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-6 mb-2">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('admin.pending.orders') }}" class="card bg-body hoverable card-xl-stretch mb-xl-8 ">
                    <!--begin::Body-->
                    <div class="card-body row text-center">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                        <span class="svg-icon svg-icon-primary svg-icon-3x ms-n1 col-3 text-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect x="8" y="9" width="3" height="10" rx="1.5" fill="black"></rect>
                                <rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black"></rect>
                                <rect x="18" y="11" width="3" height="8" rx="1.5" fill="black"></rect>
                                <rect x="3" y="13" width="3" height="6" rx="1.5" fill="black"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="col text-center text-gray-900 fw-bolder fs-5 mt-4">Total Pending Orders</div>
                        <div class="col-3 text-end">
                            <div class="text-gray-900 fw-bolder fs-2"> <span
                                    class="total-widget badge badge-light-primary">{{ $totalPendingOrders ? $totalPendingOrders : 0 }}</span>
                            </div>

                        </div>


                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-6 mb-2">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('admin.prescribed.orders') }}" class="card hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body row text-center">
                        <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                        <span class="svg-icon svg-icon-success svg-icon-3x ms-n1 col-3 text-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M18.041 22.041C18.5932 22.041 19.041 21.5932 19.041 21.041C19.041 20.4887 18.5932 20.041 18.041 20.041C17.4887 20.041 17.041 20.4887 17.041 21.041C17.041 21.5932 17.4887 22.041 18.041 22.041Z"
                                    fill="black" />
                                <path opacity="0.3"
                                    d="M6.04095 22.041C6.59324 22.041 7.04095 21.5932 7.04095 21.041C7.04095 20.4887 6.59324 20.041 6.04095 20.041C5.48867 20.041 5.04095 20.4887 5.04095 21.041C5.04095 21.5932 5.48867 22.041 6.04095 22.041Z"
                                    fill="black" />
                                <path opacity="0.3"
                                    d="M7.04095 16.041L19.1409 15.1409C19.7409 15.1409 20.141 14.7409 20.341 14.1409L21.7409 8.34094C21.9409 7.64094 21.4409 7.04095 20.7409 7.04095H5.44095L7.04095 16.041Z"
                                    fill="black" />
                                <path
                                    d="M19.041 20.041H5.04096C4.74096 20.041 4.34095 19.841 4.14095 19.541C3.94095 19.241 3.94095 18.841 4.14095 18.541L6.04096 14.841L4.14095 4.64095L2.54096 3.84096C2.04096 3.64096 1.84095 3.04097 2.14095 2.54097C2.34095 2.04097 2.94096 1.84095 3.44096 2.14095L5.44096 3.14095C5.74096 3.24095 5.94096 3.54096 5.94096 3.84096L7.94096 14.841C7.94096 15.041 7.94095 15.241 7.84095 15.441L6.54096 18.041H19.041C19.641 18.041 20.041 18.441 20.041 19.041C20.041 19.641 19.641 20.041 19.041 20.041Z"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="col text-center text-dark fw-bolder fs-5  mt-4">Total Prescribed Orders</div>
                        <div class="col-3 text-end">
                            <div class="fw-bold text-white fs-2"> <span
                                    class="total-widget badge badge-light-success">{{ $totalPrescribedOrders ? $totalPrescribedOrders : 0 }}</span>
                            </div>
                        </div>


                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-6 mb-2">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('admin.failed.refill.transaction') }}" class="card hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body row text-center">
                        <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                        <span class="svg-icon svg-icon-danger svg-icon-3x ms-n1 col-3 text-start">
                            <span class="svg-icon svg-icon-danger svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 22C7.4 22 7 21.6 7 21V9C7 8.4 7.4 8 8 8C8.6 8 9 8.4 9 9V21C9 21.6 8.6 22 8 22Z" fill="currentColor"/>
                                <path opacity="0.3" d="M4 15C3.4 15 3 14.6 3 14V6C3 5.4 3.4 5 4 5C4.6 5 5 5.4 5 6V14C5 14.6 4.6 15 4 15ZM13 19V3C13 2.4 12.6 2 12 2C11.4 2 11 2.4 11 3V19C11 19.6 11.4 20 12 20C12.6 20 13 19.6 13 19ZM17 16V5C17 4.4 16.6 4 16 4C15.4 4 15 4.4 15 5V16C15 16.6 15.4 17 16 17C16.6 17 17 16.6 17 16ZM21 18V10C21 9.4 20.6 9 20 9C19.4 9 19 9.4 19 10V18C19 18.6 19.4 19 20 19C20.6 19 21 18.6 21 18Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <!--end::Svg Icon-->
                        <div class="col text-center text-dark fw-bolder fs-5  mt-4">Total Failed Transactions</div>
                        <div class="col-3 text-end">
                            <div class="fw-bold text-white fs-2"> <span
                                    class="total-widget badge badge-light-danger">{{ $total_failed_transactions}}</span>
                            </div>
                        </div>


                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-6 mb-2">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('admin.declined.orders') }}"
                    class="card hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body row text-center">
                        <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                        <span class="svg-icon svg-icon-warning svg-icon-3x ms-n1 col-3 text-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
                                    fill="black"></path>
                                <path
                                    d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
                                    fill="black"></path>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <div class="col text-center text-dark fw-bolder fs-5 mt-2">Total Declined Orders</div>
                        <div class="col-3 text-end">
                            <div class="fw-bold text-warning fs-2"><span
                                    class="total-widget badge badge-warning">{{ $totalDeclinedOrders ? $totalDeclinedOrders : 0 }}</span>
                            </div>
                        </div>


                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
            <div class="col-xl-6">
                <!--begin::Statistics Widget 5-->
                <a href="{{ route('admin.cancelled.orders') }}"
                    class="card  hoverable card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body row text-center">
                        <!--begin::Svg Icon | path: icons/duotune/graphs/gra007.svg-->
                        <span class="svg-icon svg-icon-danger svg-icon-3x ms-n1 col-3 text-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M10.9607 12.9128H18.8607C19.4607 12.9128 19.9607 13.4128 19.8607 14.0128C19.2607 19.0128 14.4607 22.7128 9.26068 21.7128C5.66068 21.0128 2.86071 18.2128 2.16071 14.6128C1.16071 9.31284 4.96069 4.61281 9.86069 4.01281C10.4607 3.91281 10.9607 4.41281 10.9607 5.01281V12.9128Z"
                                    fill="black"></path>
                                <path
                                    d="M12.9607 10.9128V3.01281C12.9607 2.41281 13.4607 1.91281 14.0607 2.01281C16.0607 2.21281 17.8607 3.11284 19.2607 4.61284C20.6607 6.01284 21.5607 7.91285 21.8607 9.81285C21.9607 10.4129 21.4607 10.9128 20.8607 10.9128H12.9607Z"
                                    fill="black"></path>
                            </svg>
                        </span>
                        <div class="col text-dark fw-bolder fs-5 mt-2">Total Cancelled Orders</div>
                        <!--end::Svg Icon-->
                        <div class="col-3 text-end">
                            <div class="fw-bold  text-gray-100 fs-2"><span
                                    class="total-widget badge badge-light-danger">{{ $totalCancelledOrders ? $totalCancelledOrders : 0 }}</span>
                            </div>
                        </div>


                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>
        </div>
    </div>
    @if(in_array('admin.customers.list',$permissions) || Auth::user()->u_type=='superadmin') 
   <!--begin::Row-->
   <div class="row g-5 g-xl-8">
    <div class="col-xl-4">
        <a href="{{ route('admin.customers.list',['type'=>'verified_customer']) }}" class="card hoverable card-xl-stretch mb-xl-8">
            <!--begin::Body-->
            <div class="card-body row text-center">
                <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                <span class="svg-icon svg-icon-success svg-icon-3x ms-n1 col-3 text-start">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
                        <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
                        <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
                        <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
                        </svg>
                </span>
                <!--end::Svg Icon-->
                <div class="col text-center text-dark fw-bolder fs-5  mt-4">Total Verified Customers</div>
                <div class="col-3 text-end">
                    <div class="fw-bold text-white fs-2"> <span
                            class="total-widget badge badge-light-success">{{ $getVerifiedCustomers ? $getVerifiedCustomers : 0 }}</span>
                    </div>
                </div>


            </div>
            <!--end::Body-->
        </a>
    </div>
    <div class="col-xl-4">
        <a href="{{ route('admin.customers.list',['type'=>'unverified_customer']) }}" class="card hoverable card-xl-stretch mb-xl-8">
            <!--begin::Body-->
            <div class="card-body row text-center">
                <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                <span class="svg-icon svg-icon-danger svg-icon-3x ms-n1 col-3 text-start">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
                        <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
                        <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
                        <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
                        </svg>
                </span>
                <!--end::Svg Icon-->
                <div class="col text-center text-dark fw-bolder fs-5  mt-4">Total Unverified Customers</div>
                <div class="col-3 text-end">
                    <div class="fw-bold text-white fs-2"> <span
                            class="total-widget badge badge-light-danger">{{ $getUnVerifiedCustomers ? $getUnVerifiedCustomers : 0 }}</span>
                    </div>
                </div>


            </div>
            <!--end::Body-->
        </a>
    </div>
    <div class="col-xl-4">
        <a href="{{ route('admin.expired.orders') }}" class="card hoverable card-xl-stretch mb-xl-8">
            <!--begin::Body-->
            <div class="card-body row text-center">
                <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
                <span class="svg-icon svg-icon-warning svg-icon-3x ms-n1 col-3 text-start">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
                        <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
                        <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
                        <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
                        </svg>
                </span>
                <!--end::Svg Icon-->
                <div class="col text-center text-dark fw-bolder fs-5  mt-4">Total Expired Orders</div>
                <div class="col-3 text-end">
                    <div class="fw-bold text-white fs-2"> <span
                            class="total-widget badge badge-light-warning">{{ $expired_order ? $expired_order : 0 }}</span>
                    </div>
                </div>


            </div>
            <!--end::Body-->
        </a>
    </div>
   </div>
   @endif
   <!--end::Row-->
    <!--begin::Row-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xl-4">
            <!--begin::Mixed Widget 1-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Body-->
                <div class="card-body p-0">
                    <!--begin::Header-->
                    <div class="px-9 pt-7 card-rounded h-275px w-100 bg-success">
                        <!--begin::Heading-->
                        <div class="d-flex flex-stack">
                            <h3 class="m-0 text-white fw-bolder fs-3">Order Sales Summary</h3>
                            <div class="ms-1">

                            </div>
                        </div>
                        <!--end::Heading-->
                        <!--begin::Balance-->
                        <div class="d-flex text-center flex-column text-white pt-8">
                            <span class="fw-bold" style="font-size: 16px;">Total Revenue</span>
                            <span class="fw-bolder fs-2x pt-1">${{$getTotalOrders['total_amount']}}</span>
                        </div>
                        <!--end::Balance-->
                       
                    </div>
                    <!--end::Header-->
                    <!--begin::Items-->
                    <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1"
                        style="margin-top: -100px">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
                                <span class="symbol-label bg-lighten">
                                    <!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
                                    <span class="svg-icon svg-icon-2tx">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M12.025 4.725C9.725 2.425 6.025 2.425 3.725 4.725C1.425 7.025 1.425 10.725 3.725 13.025L11.325 20.625C11.725 21.025 12.325 21.025 12.725 20.625L20.325 13.025C22.625 10.725 22.625 7.025 20.325 4.725C18.025 2.425 14.325 2.425 12.025 4.725Z" fill="black"/>
                                        <path d="M14.025 17.125H13.925C13.525 17.025 13.125 16.725 13.025 16.325L11.925 11.125L11.025 14.325C10.925 14.725 10.625 15.025 10.225 15.025C9.825 15.125 9.425 14.925 9.225 14.625L7.725 12.325L6.525 12.925C6.425 13.025 6.225 13.025 6.125 13.025H3.125C2.525 13.025 2.125 12.625 2.125 12.025C2.125 11.425 2.525 11.025 3.125 11.025H5.925L7.725 10.125C8.225 9.925 8.725 10.025 9.025 10.425L9.825 11.625L11.225 6.72498C11.325 6.32498 11.725 6.02502 12.225 6.02502C12.725 6.02502 13.025 6.32495 13.125 6.82495L14.525 13.025L15.225 11.525C15.425 11.225 15.725 10.925 16.125 10.925H21.125C21.725 10.925 22.125 11.325 22.125 11.925C22.125 12.525 21.725 12.925 21.125 12.925H16.725L15.025 16.325C14.725 16.925 14.425 17.125 14.025 17.125Z" fill="black"/>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1 text-center">
                                    <span class="fs-8 text-gray-800 text-hover-primary fw-bolder">Charges pay to Smart Doctors, LLC
                                    </span>
                                    <div class="text-gray-400 fw-bold fs-7">
                                        @if ($telemedConsult == 'failure')
                                        <span class="badge badge-light-danger" style="line-height: 1.5;">Error in fetching amount from <br>Smart Doctors</span>
                                        @else
                                        <div class="fw-bolder fs-5 text-gray-800 pe-1">${{$telemedConsult}}</div>
                                        @endif
                                    </div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Item-->
                        {{--
                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
                                <span class="symbol-label bg-lighten">
                                    <!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
                                    <span class="svg-icon svg-icon-2tx">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none">
                                            <path opacity="0.3"
                                                d="M18.4 5.59998C21.9 9.09998 21.9 14.8 18.4 18.3C14.9 21.8 9.2 21.8 5.7 18.3L18.4 5.59998Z"
                                                fill="black" />
                                            <path
                                                d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM19.9 11H13V8.8999C14.9 8.6999 16.7 8.00005 18.1 6.80005C19.1 8.00005 19.7 9.4 19.9 11ZM11 19.8999C9.7 19.6999 8.39999 19.2 7.39999 18.5C8.49999 17.7 9.7 17.2001 11 17.1001V19.8999ZM5.89999 6.90002C7.39999 8.10002 9.2 8.8 11 9V11.1001H4.10001C4.30001 9.4001 4.89999 8.00002 5.89999 6.90002ZM7.39999 5.5C8.49999 4.7 9.7 4.19998 11 4.09998V7C9.7 6.8 8.39999 6.3 7.39999 5.5ZM13 17.1001C14.3 17.3001 15.6 17.8 16.6 18.5C15.5 19.3 14.3 19.7999 13 19.8999V17.1001ZM13 4.09998C14.3 4.29998 15.6 4.8 16.6 5.5C15.5 6.3 14.3 6.80002 13 6.90002V4.09998ZM4.10001 13H11V15.1001C9.1 15.3001 7.29999 16 5.89999 17.2C4.89999 16 4.30001 14.6 4.10001 13ZM18.1 17.1001C16.6 15.9001 14.8 15.2 13 15V12.8999H19.9C19.7 14.5999 19.1 16.0001 18.1 17.1001Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1 text-center">
                                    <span class="fs-8 text-gray-800 text-hover-primary fw-bolder">Current Shipping Rate</span>
                                    <div class="text-gray-400 fw-bold fs-7"> 
                                        <div class="fw-bolder fs-5 text-gray-800 pe-1">${{$shippingCost}}</div>
                                    </div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                <div class="d-flex align-items-center">
                                   

                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Item-->
                         <!--begin::Item-->
                         <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-45px w-40px me-5">
                                <span class="symbol-label bg-lighten">
                                    <!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
                                    <span class="svg-icon svg-icon-2tx">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 18H4C3.4 18 3 17.6 3 17V7C3 6.4 3.4 6 4 6H20C20.6 6 21 6.4 21 7V17C21 17.6 20.6 18 20 18ZM12 8C10.3 8 9 9.8 9 12C9 14.2 10.3 16 12 16C13.7 16 15 14.2 15 12C15 9.8 13.7 8 12 8Z" fill="currentColor"/>
                                            <path d="M18 6H20C20.6 6 21 6.4 21 7V9C19.3 9 18 7.7 18 6ZM6 6H4C3.4 6 3 6.4 3 7V9C4.7 9 6 7.7 6 6ZM21 17V15C19.3 15 18 16.3 18 18H20C20.6 18 21 17.6 21 17ZM3 15V17C3 17.6 3.4 18 4 18H6C6 16.3 4.7 15 3 15Z" fill="currentColor"/>
                                            </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Description-->
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <!--begin::Title-->
                                <div class="mb-1 pe-3 flex-grow-1 text-center">
                                    <span class="fs-8 text-gray-800 text-hover-primary fw-bolder">Total Shipping Amount Revenue</span>
                                    <div class="text-gray-400 fw-bold fs-7"> 
                                        <div class="fw-bolder fs-5 text-gray-800 pe-1">${{$getTotalShippingOrders['total_shipping_amount']}}</div>
                                    </div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Label-->
                                <div class="d-flex align-items-center">
                                   

                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Description-->
                        </div>
                        --}}
                        <!--end::Item-->
                    </div>
                    <!--end::Items-->

                </div>
                <!--end::Body-->
            </div>
            <!--end::Mixed Widget 1-->
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xl-8">
            <!--begin::Charts Widget 3-->
            <div class="card card-xl-stretch mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Order sales</span>
                        {{-- <span class="text-muted fw-bold fs-7">More than 1000 new records</span> --}}
                    </h3>
                    <!--begin::Toolbar-->
                    <div class="card-toolbar" data-kt-buttons="true">
                        <a data-url="{{ route('admin.dash.order.sales',['type'=>'week']) }}" class="btn btn-sm btn-color-muted btn-active active btn-active-primary px-4 me-1"
                            id="kt_charts_widget_3_week_btn"
                            data-order-total="{{ json_encode($getWeeklyOrders['total_amount']) }}"
                            data-order-days="{{ json_encode($getWeeklyOrders['day']) }}">Week</a>
                        <a data-url="{{ route('admin.dash.order.sales',['type'=>'month']) }}" class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1" id="kt_charts_widget_3_month_btn">Month</a>
                        <a data-url="{{ route('admin.dash.order.sales',['type'=>'year']) }}" class="btn btn-sm btn-color-muted  btn-active-primary  px-4 me-1" id="kt_charts_widget_3_year_btn">Year</a>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Chart-->
                    <div id="kt_charts_widget_3_chart" style="height: 350px"></div>
                    <!--end::Chart-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Charts Widget 3-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    {{--
    @if(in_array('admin.setting.plans',$permissions) || Auth::user()->u_type=='superadmin') 
    <div class="row">
        <!--begin::Col-->
        <div class="col">
            <!--begin::List Widget 1-->
            <div class="card card-xl-stretch mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-dark">Plans</span>

                    </h3>
                    <div class="card-toolbar">


                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-5">
                    @forelse ($plans as $plan)
                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-7">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-50px me-5">

                                <span class="symbol-label bg-light-success"
                                    style="background-image:url({{ $plan->plan_image?getImage($plan->plan_image):asset('images/webp/peaks_product.jpeg') }});"></span>

                            </div>
                            <!--end::Symbol-->
                            <!--begin::Text-->
                            <div class="d-flex flex-column">
                                <a href="{{route('admin.edit.plan',[$plan->id])}}" class="text-dark text-hover-primary fs-7 fw-bolder">{{ $plan->title }}</a>
                                <span class="text-muted fw-bold">{{ $plan->sub_title }}</span>
                            </div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->
                    @empty

                    @endforelse


                </div>
                <!--end::Body-->
            </div>
            <!--end::List Widget 1-->
        </div>
        <!--end::Col-->
    </div>
    @endif
    --}}
@endsection

@section('footer')
    @include('admin.includes.footer')
@endsection
@section('js')
    <script src="{{ asset('/admin_assets/assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('/admin_assets/assets/js/admin.home.js') }}"></script>
@endsection
