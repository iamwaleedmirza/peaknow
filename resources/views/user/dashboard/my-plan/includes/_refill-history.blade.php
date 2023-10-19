<div class="card card-flush py-4 flex-row-fluid overflow-hidden">
    <div class="card-header">
        <div class="card-title">
            <h5>Refill History</h5>
        </div>
    </div>
    <div class="card-body pt-0">

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                <thead>
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                    <th class="min-w-175px">Refill No</th>
                    <th class="min-w-175px">Refill Status</th>
                    <th class="min-w-100px">Refill Date</th>
                    <th class="min-w-100px text-center">Action</th>
                </tr>
                </thead>
                <tbody class="">

                @forelse ($order->getOrderRefills()->get() as $key => $value)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="fw-bolder text-gray-600 text-hover-primary">
                                    {{ ($value->refill_number == 0) ? 'New Fill' : "Refill ".$value->refill_number }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                            <span class="status status__danger">Cancelled</span>
                            @else
                            @if ($value->refill_status == 'Confirmed')
                                <span class="status status__info">{{ $value->refill_status }}</span>
                            @elseif ($value->refill_status == 'Completed')
                                <span class="status status__success">{{ $value->refill_status }}</span>
                            @elseif($value->refill_status == 'Pending' && $order->status == 'Prescribed')
                                <span class="status status__warning">{{ 'Requested' }}</span>
                            @elseif ($value->refill_status == 'Pending')
                                <span class="status status__warning">{{ 'Pending' }}</span>
                            @endif
                            @endif
                        </td>
                        <td>{{ $value->refill_date?\Carbon\Carbon::parse($value->refill_date)->format('M d, Y') :\Carbon\Carbon::parse($value->created_at)->format('M d, Y')}}</td>
                        <td class="text-center">
                            <div class="d-flex flex-column gap-2 flex-md-row justify-content-center">
                                <a href="{{ route('order.refill-details', [$order->order_no, $value->refill_number]) }}"
                                   target="_blank">
                                    <button class="btn btn-peaks-outline btn-small">View</button>
                                </a>
                                <a href="{{ getImage($value->invoice) }}" target="_blank">
                                    <button class="btn btn-peaks-outline btn-small">Invoice</button>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="5">No Transaction</td>
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
