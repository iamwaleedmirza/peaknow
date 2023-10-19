<div class="d-flex flex-column flex-xl-row">
    <!--begin::Sidebar-->
    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
        <!--begin::Card-->
        <div class="card mb-5 mb-xl-8">
            <!--begin::Card body-->
            <div class="card-body pt-15">
                <!--begin::Summary-->
                <div class="d-flex flex-center flex-column mb-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <img src="{{ $customer->getDocumnetByType('selfie') !== null? getImage($customer->getDocumnetByType('selfie')->document_path): asset('admin_assets/assets/media/avatars/blank.png') }}"
                            alt="image" />
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <p class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $customer->first_name }}
                        {{ $customer->last_name }}
                    </p>
                    <!--end::Name-->
                    <!--begin::Position-->
                    <div class="fs-5 fw-bold text-muted mb-6">{{ $customer->u_type }}</div>
                    <!--end::Position-->

                </div>
                <!--end::Summary-->
                <!--begin::Details toggle-->
                <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details"
                        role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Details
                        <span class="ms-2 rotate-180">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                        fill="black"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                    </div>
                </div>
                <!--end::Details toggle-->
                <div class="separator separator-dashed my-3"></div>
                <!--begin::Details content-->
                <div id="kt_customer_view_details" class="collapse show">
                    <div class="py-5 fs-6">
                        <!--begin::Badge-->
                        {{-- <div class="badge badge-light-info d-inline">Premium user</div> --}}
                        <!--begin::Badge-->
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Customer ID</div>
                        <div class="text-gray-600">{{ $customer->id }}</div>
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Liberty Patient ID</div>
                        <div class="text-gray-600">{{ $customer->liberty_patient_id ?: '-' }}</div>
                        <div class="fw-bolder mt-5 mb-1">Customer Account Status</div>
                        @if($customer->account_status==1)
                            <span class="badge badge-light-success">Active </span>
                        @else
                            <span class="badge badge-light-danger">Inactive </span>
                        @endif
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Customer Joined Date</div>
                        <div class="text-gray-600">
                            {{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('m/d/Y : H:i') : '-' }}
                        </div>
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Customer Email</div>
                        <div class="text-gray-600">
                            <p class="text-gray-600 text-hover-primary">{{ $customer->email }}
                            @if($customer->email_verified==1)
                                <span class="badge badge-light-success">Verified </span>
                            @else
                                <span class="badge badge-light-danger">Unverified</span>
                            @endif
                            </p>
                        </div>
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Customer IP Location State</div>
                        <div class="text-gray-600">
                            <p class="text-gray-600 text-hover-primary">{{ $customer->ip_location?:'-' }}</p>
                        </div>
                        <div class="fw-bolder mt-5">Customer Phone</div>
                        <div class="text-gray-600">
                            <p class="text-gray-600 text-hover-primary d-">
                                {{ empty($customer->phone) ? '-' : $customer->phone }}
                                @if (!empty($customer->phone))
                                    @if ($customer->phone_verified)
                                        <span class=" badge badge-light-success d-inline fs-8">Verified</span>
                                    @else
                                        <span class=" badge badge-light-danger d-inline fs-8">UnVerified</span>
                                    @endif
                                @endif

                            </p>
                        </div>
                        <div class="fw-bolder mt-5">Customer Gender</div>
                        <div class="text-gray-600">
                            <p class="text-gray-600 text-hover-primary">
                                {{ empty($customer->gender) ? '-' : $customer->gender }}</p>
                        </div>
                        <div class="fw-bolder mt-5">Customer Date of Birth</div>
                        <div class="text-gray-600">
                            <p class="text-gray-600 text-hover-primary">
                                {{ empty($customer->dob) ? '-' : $customer->dob }}</p>
                        </div>
                        <!--begin::Details item-->
                        <div class="fw-bolder mt-5">Customer Address</div>
                        @php
                            $address = $customer->addresses()->first();
                        @endphp
                        <div class="text-gray-600"><br>
                            @if (isset($address) && $address !== null && !empty($address->address_line) && !empty($address->zipcode) && !empty($address->city) && !empty($address->state))
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $address->address_line }}{{ $address->address_line2 ? ', ' . $address->address_line2 . ',' : '' }}
                                {{ $address->city . ', ' . $address->state . ' - ' . $address->zipcode }}
                                <br>
                            @else
                                <i class="fas fa-map-marker-alt"></i> {{ '-' }}<br>
                            @endif
                            <i class="fas fa-phone-alt"></i>
                            {{ isset($customer->phone) ? $customer->phone : 'No phone setup' }}
                        </div>

                    </div>
                </div>
                <!--end::Details content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

    </div>
    <!--end::Sidebar-->
    <!--begin::Content-->
    <div class="flex-lg-row-fluid ms-lg-15">
        <!--begin:::Tabs-->
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
            <!--begin:::Tab item-->
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                    href="#kt_customer_view_overview_tab">Overview</a>
            </li>
            <!--end:::Tab item-->
            @if( (in_array('admin.customers.change.password',$permissions) || in_array('admin.customers.account.status.update',$permissions)) || Auth::user()->u_type=='superadmin')
            <!--begin:::Tab item-->
            <li class="nav-item ms-auto">
                <!--begin::Action menu-->
                <a href="#" class="btn btn-primary ps-7 customer-action" data-kt-menu-trigger="click"
                    data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">Customer Actions
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                    <span class="svg-icon svg-icon-2 me-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </a>
                <!--begin::Menu-->
                <div class="customer-action-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold py-4 w-250px fs-6"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">Customer Settings</div>
                    </div>
                    @if(in_array('admin.customers.change.password',$permissions) || Auth::user()->u_type=='superadmin')
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a class="menu-link px-5 customer_reset_modalbtn" data-bs-toggle="modal"
                            data-user-id="{{ $customer->id }}" data-user-email="{{ $customer->email }}"
                            data-bs-target="#customer_reset_modal">Reset Password</a>
                    </div>
                    @endif
                    @if(in_array('admin.customers.account.status.update',$permissions) || Auth::user()->u_type=='superadmin')
                    <div class="menu-item px-5">
                        @if($customer->account_status==0)
                            <a class="menu-link px-5 customer_account_status_update" data-user-id="{{ $customer->id }}" data-user-status="{{ $customer->account_status }}">Enable this account</a>
                        @else
                            <a class="menu-link px-5 customer_account_status_update delete" data-user-id="{{ $customer->id }}" data-user-status="{{ $customer->account_status }}">Disable this account</a>
                        @endif
                        
                    </div>
                    @endif
                    <!--end::Menu item-->

                </div>
                <!--end::Menu-->
                <!--end::Menu-->
            </li>
            @endif
            <!--end:::Tab item-->
        </ul>
        <!--end:::Tabs-->
        <!--begin:::Tab content-->
        <div class="tab-content" id="myTabContent">
            <!--begin:::Tab pane-->
            <div class="tab-pane fade active show" id="kt_customer_view_overview_tab" role="tabpanel">

                <div class="row">
                    <div class="col-12 mb-8">
                        <!--begin::Card-->
                        @if ($customer->getDocumnetByType('govt_id') !== null)
                            <!--begin::Overlay-->
                            <a class="d-block overlay overlayBtn" hidden data-fslightbox="lightbox-basic"
                                href="{{ getImage($customer->getDocumnetByType('govt_id')->document_path) }}">


                            </a>
                            <!--end::Overlay-->
                            <div class="card overlay overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded "
                                        style="min-height:138px; background-image:url('{{ getImage($customer->getDocumnetByType('govt_id')->document_path) }}')">
                                    </div>
                                    <div class="overlay-layer bg-dark bg-opacity-25" style="opacity: 1;">
                                        <div class="row text-center">
                                            <div class="col-12">
                                                <p class="fw-bolder text-hover-primary">
                                                    <span class="badge badge-light-primary">
                                                        GOVERNMENT ISSUED PHOTO ID
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <a href="{{ getImage($customer->getDocumnetByType('govt_id')->document_path) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card overlay overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded "
                                        style="min-height:138px; background-image:url('{{ asset('/images/svg/bg_grey.svg') }}')">
                                    </div>
                                    <div class="overlay-layer bg-dark bg-opacity-25" style="opacity: 1;">
                                        <div class="row text-center">
                                            <div class="col-12">
                                                <p class="fw-bolder text-hover-primary">
                                                    <span class="badge badge-light-primary">
                                                        GOVERNMENT ISSUED PHOTO ID
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <a href="javascript:void(0)" class="btn btn-danger btn-shadow">No
                                                    Document found</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--end::Card-->
                    </div>
                    <div class="col-12">
                           <!--begin::Card-->
                           <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bolder">Total Spending</h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">

                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            @php
                                if ($customer->getOrderTotal($customer->id) != null) {
                                    $customerTotal = $customer->getOrderTotal($customer->id);
                                } else {
                                    $customerTotal = 0;
                                }
                            @endphp
                            <div class="card-body pt-0">
                                <div class="fw-bolder fs-2">${{ $customerTotal }}
                                    {{-- <span class="text-muted fs-4 fw-bold">USD</span> --}}
                                    <div class="fs-7 fw-normal text-muted"></div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>

                </div>

                <!--begin::Card-->
                <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Order Records</h2>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Filter-->

                            <!--end::Filter-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_payment">
                            <!--begin::Table head-->
                            <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                <!--begin::Table row-->
                                <tr class="text-start text-uppercase gs-0">
                                    <th class="">Order No</th>
                                    <th>Order Status</th>
                                    <th>Order Amount</th>
                                    <th class="">Ordered Date</th>
                                    <th class="pe-4">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 ">
                                @forelse ($customer->orders()->orderBy('created_at','DESC')->get() as $order)
                                    <!--begin::Table row-->
                                    <tr>
                                        <!--begin::Invoice=-->
                                        <td>

                                                <a href="{{ route('admin.view.orders', [$order]) }}"
                                                class="text-dark"><div class="text-dark text-hover-primary">#PC-{{ $order->order_no }}</div></a>


                                        </td>
                                        <!--end::Invoice=-->
                                        <!--begin::Status=-->
                                        <td>
                                            @if ($order->status == 'Cancelled')
                                                <span class="badge badge-light-danger"> Cancelled</span>
                                            @elseif($order->status == 'Prescribed')
                                                <span class="badge badge-light-success"> {{ $order->status }}</span>
                                            @elseif($order->status == 'Declined')
                                                <span class="badge badge-light-danger"> {{ $order->status }}</span>
                                            @elseif($order->status == 'Pending')
                                                <span class="badge badge-light-warning"> {{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <!--end::Status=-->
                                        <!--begin::Amount=-->
                                        <td>${{ $order->total_price }}</td>
                                        <!--end::Amount=-->
                                        <!--begin::Date=-->
                                        <td> {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('m/d/Y : H:i') : '-' }}
                                        </td>
                                        <!--end::Date=-->
                                        <!--begin::Action=-->
                                        <td class="pe-0">
                                            <a href="{{ route('admin.view.orders', [$order]) }}" target="_blank"
                                                class="btn btn-sm btn-success btn-hover-rise ms-auto me-lg-n7">View
                                            </a>

                                        </td>
                                        <!--end::Action=-->
                                    </tr>
                                    <!--end::Table row-->
                                @empty
                                @endforelse
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->

                </div>
                    
                <!--end::Card-->

                @if ($customer->customerPaymentLogs()->count() !== 0)
                <!--begin::Payment Logs-->
                    <div class="card py-4 flex-row-fluid overflow-hidden mb-8">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                Payment Logs
                            </div>
                            <!--end::Card title-->
                            @include('admin.includes.export-btn')
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="kt_ecommerce_sales_table" class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                    <tr class="text-start fw-bolder text-uppercase gs-0">
                                        <th class="min-w-150px">Order #</th>
                                        <th class="min-w-125px">Payment For</th>
                                        <th>Event</th>
                                        <th>Status</th>
                                        <th class="min-w-125px">Transaction ID</th>
                                        <th>Amount</th>
                                        <th class="min-w-200px">Date</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >

                                    <!--begin::Orders-->
                                    @forelse ($customer->customerPaymentLogs()->latest()->get() as $key => $value)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.view.orders', ['order' => \App\Models\Order::getOrderId($value->order_no)]) }}" class="text-dark">
                                                    <div class="text-dark text-hover-primary">#PC-{{ $value->order_no }}</div>
                                                </a>
                                            </td>
                                            <td><div class="badge badge-light-info">{{ $value->payment_for ?? 'None' }}</div></td>
                                            <td><div class="badge badge-light-primary">{{ $value->event_type ?? 'None' }}</div></td>
                                            <td><div class="badge {{ $value->status == 'SUCCESS' ? 'badge-light-success' : 'badge-light-danger' }}">{{ $value->status ?? 'None' }}</div></td>
                                            <td>{{ $value->transaction_id ?? 'None' }}</td>
                                            <td>{{ '$'.$value->amount ?? 'None' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('m/d/Y H:i a') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">No Logs</td>
                                        </tr>
                                    @endforelse
                                    <!--end::Orders-->
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                    <!--end::Card body-->
                    </div>
                <!--end::Payment Logs-->
                @endif

                @if ($customer->authorizeCustomerLogs()->count() !== 0)
                <!--begin::Payment Logs-->
                    <div class="card py-4 flex-row-fluid overflow-hidden mb-4">
                        <!--begin::Card header-->
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <!--begin::Card title-->
                            <div class="card-title">Customer Logs</div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table id="tableCustomerLogs" class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <!--begin::Table head-->
                                    <thead>
                                    <tr class="text-start fw-bolder text-uppercase gs-0">
                                        <th>Event</th>
                                        <th>Status</th>
                                        <th class="min-w-200px">Date</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody >

                                    <!--begin::Orders-->
                                    @forelse ($customer->authorizeCustomerLogs()->latest()->get() as $key => $value)
                                        <tr>
                                            <td><div class="badge badge-light-primary">{{ $value->event_type ?? 'None' }}</div></td>
                                            <td><div class="badge {{ $value->status == 'SUCCESS' ? 'badge-light-success' : 'badge-light-danger' }}">{{ $value->status ?? 'None' }}</div></td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('m/d/Y H:i a') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">No Logs</td>
                                        </tr>
                                    @endforelse
                                    <!--end::Orders-->
                                    </tbody>
                                    <!--end::Table head-->
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Payment Logs-->
                @endif


                   {{-- <!--begin::Card-->
                   <div class="card pt-4 mb-6 mb-xl-9">
                    <!--begin::Card header-->
                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>Customer Address</h2>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Filter-->

                            <!--end::Filter-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0 pb-5">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_customers_address">
                            <!--begin::Table head-->
                            <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                <!--begin::Table row-->
                                <tr class="text-start text-uppercase gs-0">
                                    <th class="">Address</th>
                                    <th class="">Address Created Date</th>
                                    <th class="pe-4">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fs-6 ">
                                @forelse ($customer->addresses()->orderBy('created_at','DESC')->get() as $address)
                                    <!--begin::Table row-->
                                    <tr>
                                        <!--begin::Address=-->
                                        <td>
                                            <p>
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $address->address_line }}{{ $address->address_line2 ? ', ' . $address->address_line2 . ',' : '' }}
                                                {{ $address->city . ', ' . $address->state . ' - ' . $address->zipcode }}
                                                <br>
                                            </p>
                                        </td>
                                        <!--end::Address=-->
                                        <!--begin::Date=-->
                                        <td> {{ $address->created_at ? \Carbon\Carbon::parse($address->created_at)->format('m/d/Y : H:i') : '-' }}
                                        </td>
                                        <!--end::Date=-->
                                        <!--begin::Action=-->
                                        <td class="pe-0">
                                            <a href="#" target="_blank"
                                                class="btn btn-sm btn-success btn-hover-rise ms-auto me-lg-n7">View
                                            </a>

                                        </td>
                                        <!--end::Action=-->
                                    </tr>
                                    <!--end::Table row-->
                                @empty
                                @endforelse
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->

                </div>
                <!--end::Card--> --}}
            </div>

        </div>
        <!--end:::Tab content-->
    </div>
    <!--end::Content-->
</div>

<div class="text-center next-previous-footer">
        @if($previous)
            <a href="{{route('admin.customers.view', [$previous->id])}}" class="px-3 fs-4"><i class="fa fa-angle-left fs-4" aria-hidden="true"></i> Previous Customer </a>
        @else
            <a href="javascript:void(0)" class="px-3 fs-4 text-muted pointer-none"><i class="fa fa-angle-left fs-4 text-muted pointer-none" aria-hidden="true"></i> Previous Customer </a>
        @endif
        |
        @if($next)
            <a href="{{route('admin.customers.view', [$next->id])}}" class="px-3 fs-4">Next Customer <i class="fa fa-angle-right fs-4" aria-hidden="true"></i></a>
        @else
            <a href="javascript:void(0)" class="px-3 fs-4 text-muted pointer-none">Next Customer <i class="fa fa-angle-right fs-4 text-muted pointer-none" aria-hidden="true"></i></a>
        @endif
    </div>
<script>
    var hostUrl = "admin_assets/assets/";
    // var table = document.querySelector('#kt_table_customers_payment');
    // datatable = $(table).DataTable({
    //     "info": false,
    //     'order': [],
    //     "pageLength": 5,
    //     "lengthChange": false,
    //     'columnDefs': [{
    //             orderable: false,
    //             targets: 4
    //         }, // Disable ordering on column 5 (actions)
    //     ]
    // });
    var table = document.querySelector('#kt_table_customers_address');
    datatable = $(table).DataTable({
        "info": false,
        'order': [],
        "pageLength": 5,
        "lengthChange": false,
        // 'columnDefs': [{
        //         orderable: false,
        //         targets: 4
        //     }, // Disable ordering on column 5 (actions)
        //]
    });

    $('#tableCustomerLogs,#kt_table_customers_payment,#kt_ecommerce_sales_table').DataTable({'order': []});

</script>
<script src="{{ asset('/admin_assets/assets/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
<script>
    var docType = "Payment Logs";
</script>
<script src="{{ asset('admin_assets/assets/js/custom/apps/ecommerce/sales/sub-listing.js') }}"></script>
<!--begin::Global Javascript Bundle(used by all pages)-->
