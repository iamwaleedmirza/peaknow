@extends('user.dashboard.dashboard')

@section('title') My Orders @endsection

@section('css')
    <style>
        .card {
            border: 1px solid var(--text-color-secondary);
        }

        .card-header {
            border-bottom: 1px solid var(--text-color-secondary);
        }

        .card-footer {
            border-top: 1px solid var(--text-color-secondary);
        }

        .pull-up {
            transition: all 0.25s ease;
        }

        .pull-up:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 14px 24px rgba(62, 57, 107, 0.2);
            z-index: 30;
        }

        .product-img-wrapper {
            height: 100px;
            width: 100px;
            position: relative;
        }

        .product-img-wrapper img {
            height: 100%;
            width: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn-small {
            height: auto;
        }
    </style>
@endsection

@section('content')

    <div class="row justify-content-center mx-0 px-0">
        <div class="col-12 col-md-12 px-0">
            <div class="order-tabs">

                <h4 class="mb-4">My Orders</h4>

                @if(count($orders) > 0)
                    @foreach($orders as $order)
                        <div class="card pull-up my-3">
                            <div class="card-header">
                                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                                    <div class="text-center mb-2 mb-md-0">
                                        Order
                                        <a href="{{ route('order-details', $order->order_no) }}" class="t-link">
                                            #{{ 'PC-'.$order->order_no }}
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('order-details', $order->order_no) }}" class="me-1">
                                            <button class="btn btn-peaks-outline btn-small">
                                                View Details
                                            </button>
                                        </a>
                                        @if($order->payment_status == 'Paid' && $order->invoice)
                                            <a href="{{ getImage($order->invoice) }}" target="_blank">
                                                <button class="btn btn-peaks-outline btn-small">
                                                    <i class="far fa-file-alt me-1"></i> Invoice
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body py-0">
                                    <div class="row justify-content-center mx-0 px-0">
                                        <div class="col-12 col-md-3 col-lg-2">
                                            <div class="pt-3">
                                                <div class="product-img-wrapper d-flex justify-content-center">
                                                    <img class="img-fluid" src="{{ !empty($order->product_image) ? getImage($order->product_image) : asset('images/webp/product-img-1.webp') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-8 col-lg-5">
                                            <div class="pt-3">
                                                <p class="my-0 t-bold">{{ $order['product_name'] }} ({{ $order['medicine_variant'] }})</p>
                                                <p class="my-0 t-bold">{{ $order['plan_name'] }} ({{ $order['plan_title'] }})</p>
                                                <p class="my-0 t-bold">{{ $order['product_quantity'] }} X {{ $order['strength' ]}}mg</p>
                                                
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="d-flex flex-column align-items-start flex-md-row justify-content-md-center flex-xl-column align-items-xl-end gap-md-3 gap-lg-0 pt-3">
                                                @php
                                                    if(!empty($order)) {
                                                        $status = $order->status;
                                                        if ($status === 'Pending') {
                                                            $statusClass = 'status__info';
                                                            $order->status = 'Confirmed';
                                                        }
                                                        if ($status === 'Declined') $statusClass = 'status__danger';
                                                        if ($status === 'Prescribed') $statusClass = 'status__primary';
                                                        if ($status === 'Delivered') $statusClass = 'status__success';
                                                        if ($status === 'Cancelled') $statusClass = 'status__danger';
                                                    }
                                                    if(!empty($order)) {
                                                        $p_status = 'Pending';
                                                        if ($order->payment_status == 'Paid') {
                                                            $p_statusClass = 'status__success';
                                                            $p_status = 'Paid';
                                                        } else {
                                                            $p_statusClass = 'status__warning';
                                                            $p_status = 'Pending';
                                                        }
                                                    }
                                                @endphp
                                                @if($order->cancellation_request!=1)
                                                <p class="my-0 mb-3">
                                                    Order status: <span class="status {{ $statusClass }}">{{ ($order->status == 'Cancelled') ? 'Cancelled' : $order->status }}</span>
                                                </p>
                                                @endif
                                                <p class="my-0 mb-3">
                                                    Payment status: <span class="status {{ $p_statusClass }}">{{ $p_status }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-lg-3">
                                <div class="d-flex flex-column flex-md-row justify-content-md-between">
                                    <div class="">
                                        <span class="t-color">Ordered On</span>
                                        <span class="t-color"><strong>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</strong></span>
                                    </div>
                                    <div class="">
                                        <span class="t-color">Total Amount</span>
                                        <span class="t-color"><strong>${{ $order->total_price }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="d-flex justify-content-center align-items-center mt-0 mt-md-5">
                        <div class="card d-flex flex-column justify-content-center align-items-center p-4">
                            <img src="{{ asset('images/svg/empty_cart.svg') }}" class="img-empty-data mb-4" alt=""/>
                            <h6>You have no orders yet!</h6>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- cancel Modal -->
    <div class="modal fade" id="cancel_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-start" id="exampleModalLabel"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cancel.order') }}" method="post">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="text-start mb-2">Please write down reason for cancellation *</label>
                            <textarea placeholder="write here..." name="cancel_reason" rows="4"
                                      class="input-primary" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-peaks">Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#user-order-table').DataTable();

            @if(session()->has('success'))
                showToast('success', '{{ session()->get('success') }}');
            @endif
            @if(session()->has('error'))
                showToast('error', '{{ session()->get('error') }}');
            @endif
        });
    </script>
@endsection
