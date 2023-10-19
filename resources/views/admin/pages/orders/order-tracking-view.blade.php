        <!--begin::Order tracking-->
        <div class="card card-flush py-4 flex-row-fluid flex-fill overflow-hidden">
            <div class="card-header">
                <div class="card-title">
                    <h2>Order Tracking</h2>
                </div>
            </div>
            <div class="card-body">

            @php
                $orderConfirmed = false;
                $orderConfirmedMsg = 'Payment Pending';
                $doctorConfirmed = false;
                $doctorConfirmedMsg = 'Pending';
                $shippingConfirmed = false;
                $shippingConfirmedMsg = 'Not Delivered';

                $cancelled = false;

                if ($order->payment_status == 'Paid') {
                    $orderConfirmed = true;
                    $orderConfirmedMsg = 'Order Confirmed';

                    if ($order->status == 'Pending') {
                        $doctorConfirmedMsg = 'Awaiting Consultation';
                    }
                    if ($order->status == 'Declined') {
                        $doctorConfirmed = true;
                        $doctorConfirmedMsg = 'Declined';
                        $cancelled = true;
                    }
                    if ($order->status == 'Prescribed') {
                        $doctorConfirmed = true;
                        $doctorConfirmedMsg = 'Prescribed';
                    }
                    if ($order->status == 'Cancelled') {
                        $cancelled = true;
                        $doctorConfirmed = true;
                        $doctorConfirmedMsg = 'Withdrawn';
                        $shippingConfirmed = false;
                        $shippingConfirmedMsg = 'Not Delivered';
                    }
                    if ($orderShipment) {
                        if (strtotime(date('Y-m-d')) >= strtotime($orderShipment->ship_date)) {
                            $shippingConfirmed = true;
                        }
                    }
                }

            @endphp

            <!--large screens-->
                <div class="d-none d-sm-block">
                    <div
                        class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">

                        <div class="step {{ ($orderConfirmed) ? 'completed' : '' }}">
                            <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                            </div>
                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $orderConfirmedMsg }}</h6>
                            <p class="t-small">
                                {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : '-' }}
                            </p>
                        </div>

                        <div
                            class="step {{ ($doctorConfirmed) ? 'completed' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                            <div class="step-icon-wrap">
                                <div class="step-icon"><i class="fas fa-user-md"></i></div>
                            </div>
                            <h6 class="step-title fw-bold mb-1 mt-3">{{ $doctorConfirmedMsg }}</h6>
                            @if($doctorConfirmed)
                                <p class="t-small">
                                    {{ $order->prescribed_date ? \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y') : \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}
                                </p>
                            @endif
                        </div>

                        @if($cancelled)
                            <div class="step completed order__error">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-times"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3">Order Cancelled</h6>
                                <p class="t-small">
                                    {{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') : '-' }}
                                </p>
                            </div>
                        @endif

                        @if(!$cancelled)
                            <div
                                class="step {{ ($shippingConfirmed) ? 'completed' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                                <div class="step-icon-wrap">
                                    <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
                                </div>
                                <h6 class="step-title fw-bold mb-1 mt-3 {{ ($shippingConfirmed) ? '' : 'text-muted' }}">Shipped (New Fill)</h6>
                                @if ($shippingConfirmed)
                                    <p class="t-small">{{ \Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y') }}</p>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                <!--mobile-->
                <div class="d-block d-sm-none">
                    <div class="row orderstatus-container">
                        <div class="medium-12 columns">
                            <div class="orderstatus {{ ($orderConfirmed) ? 'done' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-shopping-cart"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    <time>{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : '-' }}</time>
                                    <p>{{ $orderConfirmedMsg }}</p>
                                </div>
                            </div>
                            <div
                                class="orderstatus {{ ($doctorConfirmed) ? 'done' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                                <div class="orderstatus-check">
                                    <span class="orderstatus-number"><i class="fas fa-user-md"></i></span>
                                </div>
                                <div class="orderstatus-text">
                                    @if($doctorConfirmed)
                                        <time>{{ $order->prescribed_date ? \Carbon\Carbon::parse($order->prescribed_date)->format('M d, Y') :  \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}</time>
                                    @endif
                                    <p>{{ $doctorConfirmedMsg }}</p>
                                </div>
                            </div>
                            @if($cancelled)
                                <div class="orderstatus done order__error">
                                    <div class="orderstatus-check">
                                        <span class="orderstatus-number"><i class="fas fa-times"></i></span>
                                    </div>
                                    <div class="orderstatus-text">
                                        <time>{{ $order->updated_at ? \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') : '-' }}</time>
                                        <p>Order Cancelled</p>
                                    </div>
                                </div>
                            @endif
                            @if(!$cancelled)
                                <div
                                    class="orderstatus {{ ($shippingConfirmed) ? 'done' : '' }} {{ ($cancelled) ? 'order__error' : '' }}">
                                    <div class="orderstatus-check">
                                        <span class="orderstatus-number"><i class="fas fa-shipping-fast"></i></span>
                                    </div>
                                    <div class="orderstatus-text">
                                        @if ($shippingConfirmed)
                                            <time>{{ \Carbon\Carbon::parse($orderShipment->ship_date)->format('M d, Y') }}</time>
                                        @endif
                                        <p>Shipped (New Fill)</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($orderShipment && $orderShipment->tracking_number)
                    <div class="text-start text-md-center">
                        <a href="https://www.fedex.com/fedextrack/?trknbr={{ $orderShipment->tracking_number }}"
                           target="_blank" class="t-link">
                            Tracking Number: {{ $orderShipment->tracking_number }}
                        </a>
                    </div>
                @endif

            </div>
        </div>
        <!--end::Order tracking-->
