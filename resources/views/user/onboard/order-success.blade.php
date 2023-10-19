@extends('user.base.main')
@section('title') Order Successful @endsection

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
                <p class="mb-0">Your Order has been successfully placed.</p>
                <p>Your Order ID is #PC-{{ $order->order_no }}</p>
                <div class="card p-3 card-highlight">
                    <h4 class="t-color-dark text-red">You Are Almost DONE!!!</h4>
                    <p>You will receive a text message from our medical providers. Please respond with any questions for
                        the doctor or simply respond "No" but </p>
                    <h5 class="text-red">Your response will be required.</h5>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                <a href="{{ route('order-details', ['orderId'=>$order->order_no]) }}">
                    <button class="btn btn-peaks-outline">
                        <i class="fas fa-home me-2"></i> View Order
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

        $(document).ready(function () {
            @if(session()->has('successful'))
            showToast('success', '{{ session()->get('successful') }}');
            @endif
            @if(session()->has('error'))
            showToast('error', '{{ session()->get('error') }}');
            @endif
        });

    </script>
@endsection
