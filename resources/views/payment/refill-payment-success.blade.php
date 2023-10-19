@extends('user.base.main')
@section('title') Refill Request Successful @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <style>
        .success-img-wrapper {
            width: 100%;
            height: auto;
            max-width: 300px;
            margin: 1rem auto;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-center">
        <div class="card d-inline-block mx-3 p-4 mb-4 mw-550px">
            <div class="success-img-wrapper">
                <img src="{{ asset('images/svg/thankyou.svg') }}">
            </div>
            <div class="text-center mb-4">
                <h3>Thank You!</h3>
                <p class="mb-0">Your Refill request is generated for order #PC-{{ $order->order_no }}.</p>
                <p>We'll let you know when your refill order is shipped with tracking number.</p>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                <a href="{{ route('order.refill-details', ['orderId'=>$order->order_no, 'refillNo' => $order->refill_count]) }}">
                    <button class="btn btn-peaks-outline">
                        View Refill Details
                    </button>
                </a>
                @if($order->payment_status == 'Paid' && $order->invoice)
                    <a href="{{ getImage($order->invoice) }}" target="_blank">
{{--                        @php--}}
{{--                            session()->put('invoice_order_id', $order->id)--}}
{{--                        @endphp--}}
                        <button class="btn btn-peaks">
                            <i class="far fa-file-alt me-2"></i> View Invoice
                        </button>
                    </a>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">

    </script>
@endsection
