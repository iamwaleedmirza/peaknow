@extends('user.base.main')
@section('title') Invoice | Payment Successful @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-lg-8">
                <div class="mb-2">
                    @if (\Request::get('type') !=='admin')




                    <div class="alert status__success text-center mb-5">
                        <div class="d-flex justify-content-around align-items-center">
                            <h6 class="mb-0">Congratulations! Your payment is successful.</h6>
                            <a class="mb-0" href="{{ route('account-orders') }}">
                                <button class="btn btn-peaks-outline btn-small">Go to Orders</button>
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="card p-2 pb-5 mb-4">
                        <table class="mb-5">
                            <tbody>
                            <tr class="mb-3">
                                <td class="text-start ps-5 pt-5">
                                    <h3 class="h-color-dark t-bold mb-4">Invoice</h3>

                                    <p class="h-color-dark mb-0"><strong>Order No:</strong> #{{ $order->order_no }}</p>
                                    <p class="h-color-dark">
                                        <strong>Order Date:</strong>
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                    </p>
                                </td>
                                <td class="text-end">
                                    <img src="{{ asset('images/svg/peaks-logo-dark.svg') }}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start ps-5">
                                    <div style="max-width: 200px;">
                                        <p class="h-color-dark mb-0"><strong>Billed To:</strong></p>
                                        {{-- <p class="h-color-dark mb-1">
                                            {{ $order->first_name.' '.$order->last_name }}
                                        </p> --}}
                                        <p class="h-color-dark mb-0">
                                            {{ $order->shipping_fullname }} <br>
                                            {{ $order->shipping_address_line }} {{ $order->shipping_city.', '.$order->shipping_state.' - '.$order->shipping_zipcode }}
                                            <br>
                                            {{ $order->shipping_phone }}
                                        </p>
                                    </div>
                                </td>
                                <td class="text-end pe-5">
                                    <p class="h-color-dark mb-0"><strong>Seller:</strong></p>
                                    <p class="h-color-dark mb-0">Peaks Curative</p>
                                    <p class="h-color-dark mb-0">
                                        Street address, <br>
                                        City, State, <br>
                                        Country, Zip code
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="px-5">
                            <table class="table border">
                                <thead class="text-start">
                                <tr class="h-color-dark">
                                    <th>Payment method</th>
                                    <th class="text-end">Transaction ID</th>
                                </tr>
                                </thead>
                                <tbody class="text-start">
                                <tr class="h-color-dark">
                                    <td>{{ $order->payment_method }}</td>
                                    <td class="text-end">{{ $order->transaction_id }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="px-5">
                            <table class="table border">
                                <thead class="text-start">
                                <tr class="h-color-dark">
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                </tr>
                                </thead>
                                <tbody class="text-start">
                                <tr class="h-color-dark">
                                    <td>{{ $order->product_name }}</td>
                                    <td class="text-end">${{ $order->product_price }}</td>
                                </tr>
                                <tr class="h-color-dark">
                                    <td>Telemedicine Consult</td>
                                    <td class="text-end">$30</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="px-5">
                            <div class="d-flex justify-content-end">
                                <table class="h-color-dark">
                                    <tbody class="text-end">
                                    <tr>
                                        <td class="text-end t-semi pe-2">Sub-Total:</td>
                                        <td>${{ $order->product_price + $order->telemedConsult }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end t-semi pe-2">Shipping cost:</td>
                                        <td>${{ $order->shippingCost }}</td>
                                    </tr>
                                    <!--begin::discount-->
                                    <tr>
                                        <td class="text-end t-semi pe-2">Discount (Promo Code - PEAKS101):</td>
                                        <td>-${{ $order->discount  }}</td>
                                    </tr>
                                    <!--end::discount-->
                                    </tbody>
                                </table>
                            </div>
                            <hr class="bg-dark">
                            <p class="h-color-dark text-end">
                                <strong>Total: ${{ $order->total_price }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mb-5">
                        <a href="{{ route('download-invoice', $order->id) }}" target="_blank">
                            <button class="btn btn-peaks-hollow btn-small">
                                <i class="fas fa-cloud-download-alt"></i>
                                Download this Invoice
                            </button>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript">

    </script>
@endsection
