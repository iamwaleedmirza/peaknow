<div class="card card-flush py-4 flex-row-fluid flex-fill overflow-hidden">
    <div class="card-header">
        <div class="card-title">
            <h2>Refills Tracking</h2>
        </div>
    </div>
    <div class="card-body">

    @php
        $dateArray = [];
        $dateArray2 = [];
        foreach ($order->orderRefill()->get() as $key => $value){
            array_push($dateArray, $value->refill_date);
            array_push($dateArray2, $value->created_at);
        }
       $refillsCount = 5;
    @endphp

    <!--large screens-->
        <div class="d-none d-sm-block">
            <div id="refill" class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">

                @for($i = 0; $i <= $refillsCount; $i++)
                    <div class="step {{ ($i <= $order->refill_count && $order->status == 'Prescribed') ? 'completed' : '' }} {{ ($i <= $order->refill_count && ($order->status == 'Cancelled' || $order->status == 'Declined')) ? 'order__error' : '' }}">
                        @if($i <= $order->refill_count) <a href="javascript:void(0)" id="view-refill-track" data-uri="{{ route('admin.order.refill.data', [$order->id, $i]) }}"> @endif
                                <div class="step-icon-wrap">
                                    <div class="step-icon step-refill-track">
                                        @if ($order->status == 'Cancelled' || $order->status == 'Declined')
                                        <i class="fas fa-times"></i>
                                        @else
                                        <i class="fas fa-check"></i>
                                        @endif
                                    </div>
                                </div>
                                @if($i <= $order->refill_count)
                                    <h6 class="step-title fw-bold h-color mb-1 mt-3">
                                        <a href="javascript:void(0)" id="view-refill-track" data-uri="{{ route('admin.order.refill.data', [$order->id, $i]) }}"
                                           class="t-link h-color">
                                            {{ ($i == 0) ? 'New Fill' : "Refill ${i}" }}
                                        </a>
                                    </h6>
                                    <p class="t-small">
                                        {{ $dateArray[$i]?\Carbon\Carbon::parse($dateArray[$i])->format('M d, Y'):\Carbon\Carbon::parse($dateArray2[$i])->format('M d, Y') }}
                                    </p>
                                @else
                                    <h6 class="step-title mb-1 mt-3">
                                        {{ ($i == 0) ? 'New Fill' : "Refill ${i}" }}
                                    </h6>
                                @endif
                            @if($i <= $order->refill_count) </a> @endif
                    </div>
                @endfor

            </div>
        </div>

        <!--mobile-->
        <div class="d-block d-sm-none">
            <div class="row orderstatus-container">
                <div id="refill-mb" class="medium-12 columns">

                    @for($i = 0; $i <= $refillsCount; $i++)
                        <div class="orderstatus {{ ($i <= $order->refill_count && $order->status == 'Prescribed') ? 'done' : '' }} {{ ($i <= $order->refill_count && ($order->status == 'Cancelled' || $order->status == 'Declined')) ? 'order__error' : '' }}">
                            <div class="orderstatus-check">
                                <span class="orderstatus-number"></span>
                            </div>
                            <div class="orderstatus-text">
                                @if($i <= $order->refill_count)
                                    <time>
                                        {{ $dateArray[$i]?\Carbon\Carbon::parse($dateArray[$i])->format('M d, Y'):\Carbon\Carbon::parse($dateArray2[$i])->format('M d, Y') }}
                                    </time>
                                    <p class="{{ ($i <= $order->refill_count) ? 't-bold h-color' : '' }}">
                                        <a href="javascript:void(0)" id="view-refill-track" data-uri="{{ route('admin.order.refill.data', [$order->id, $i]) }}"
                                           class="t-link h-color">
                                            {{ ($i == 0) ? 'New Fill' : "Refill ${i}" }}
                                        </a>
                                    </p>
                                @else
                                    <p>
                                        {{ ($i == 0) ? 'New Fill' : "Refill ${i}" }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endfor

                </div>
            </div>
        </div>

    </div>
</div>
