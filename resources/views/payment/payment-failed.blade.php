@extends('user.base.main')
@section('title') Payment Failed @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
    <style>
        .success-img-wrapper {
            width: 100%;
            height: auto;
            max-width: 240px;
            margin: 1rem auto;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-center">
        <div class="card d-inline-block mx-3 p-4 mb-4 mw-550px">
            <div class="success-img-wrapper">
                <img src="{{ asset('images/svg/failed.svg') }}">
            </div>
            <div class="text-center mb-4">
                <h4>Payment failed!</h4>
                <p class="mb-0">Your payment for Order ID <strong>#PC-{{ $order->order_no }}</strong> has been failed.</p>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                <a href="{{ route('account-home') }}">
                    <button class="btn btn-peaks-outline">
                        <i class="fas fa-home me-2"></i> Go to Home
                    </button>
                </a>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
    </script>
@endsection
