@extends('user.dashboard.dashboard')
@section('title') Invoice @endsection

@section('css')

@endsection

@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-lg-12">
                <div class="mb-2">
                    <div class="card p-2 pb-5 mb-4">
                        <table class="mb-5">
                            <tbody>
                            <tr class="mb-3">
                                <td class="text-start ps-5 pt-5">
                                    <h3 class="h-color-dark t-bold mb-4">Invoice</h3>
                                    <p class="h-color-dark mb-0"><strong>Order No:</strong> #{{ $order->order_no }}</p>
                                    <p class="h-color-dark mb-0">
                                        <strong>Order Date:</strong>
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                    </p>
                                    <p class="h-color-dark">
                                        <strong>Transaction Date:</strong>
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                                    </p>
                                </td>
                                <td class="text-end">
                                    <img src="{{ asset('images/svg/peaks-logo-dark.svg') }}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start ps-5">
                                    <div style="max-width: 180px;">
                                        <p class="h-color-dark mb-0"><strong>Billed To:</strong></p>
                                        <p class="h-color-dark mb-1">
                                            {{ $tr_details->getPaymentProfile()->getbillTo()->getFirstName().' '.$tr_details->getPaymentProfile()->getbillTo()->getLastName() }}
                                        </p>
                                        <p class="h-color-dark mb-0">
                                            {{ $tr_details->getPaymentProfile()->getbillTo()->getAddress() }},
                                        </p>
                                        <p class="h-color-dark mb-0">
                                            {{ $tr_details->getPaymentProfile()->getbillTo()->getCity().', '.$tr_details->getPaymentProfile()->getbillTo()->getState() }},
                                        </p>
                                        <p class="h-color-dark mb-0">
                                            {{ $tr_details->getPaymentProfile()->getbillTo()->getCountry().', '.$tr_details->getPaymentProfile()->getbillTo()->getZip() }}
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
                                    <td>{{ ($tr_details->getPaymentProfile()->getPayment()->getCreditCard() != null)?'Through Card':'Through Bank Account' }}</td>
                                    <td class="text-end">{{ $transaction->transaction_id }}</td>
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
                                    <td>Subscription Plan Price</td>
                                    <td class="text-end">${{$transaction->amount}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="px-5">
                            <hr class="bg-dark">
                            <p class="h-color-dark text-end">
                                <strong>Total: ${{ $order->total_price }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mb-5">
                        <a href="{{ route('plan.download-invoice', $order->id) }}" target="_blank">
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
