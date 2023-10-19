@if ($order->cancellation_request)
    <div class="card card-flush py-4 flex-row-fluid flex-fill dr-pres">

        <div class="card-header">
            <div class="card-title">
                <h6><i class="fas fa-info-circle me-3"></i><span>Order cancellation is now underway.</span></h6>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive pt-3">
                <p class="mb-0">
                    An order cancellation request has been generated on <strong>{{ \Carbon\Carbon::parse($order->cancellation_request_date)->format('F j, Y') }}</strong>.
                    Upon complete cancellation of the order, a full refund will be issued.
                </p>
            </div>
        </div>
    </div>
@endif
