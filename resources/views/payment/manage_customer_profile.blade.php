@extends('user.base.main')
@section('title') Create Profile @endsection
@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-md-8">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger mt-3">
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">×</button>
                        <span>{{ $message }}</span>
                    </div>
                @endif
                <div id="iframe_holder" class="center-block" style="margin-bottom: 20px;"
                     data-mediator="payment-form-loader">
                    <iframe id="load_payment" class="embed-responsive-item" name="load_payment" width="100%" height="600" scrolling="yes">
                    </iframe>
                    <form id="send_hptoken" action="{{ config('services.authorize.customer_manage_url') }}" method="post" target="load_payment">
                        <input type="hidden" name="token" value="{{ $token }}"/>
                    </form>
                    <div class="d-flex flex-column flex-wrap align-items-center flex-md-row justify-content-md-center gap-2 gap-md-4 py-2">
                        <a href="{{route('account-home')}}">
                            <button id="btn-peaks" class="btn btn-peaks-outline">Cancel</button>
                        </a>
                        <button id="complete_order" class="btn btn-peaks" data-uri="{{route('create-customerPayment-profile', ['order_id' => $order_id, 'customer_profile_id' => $customer_profile_id]) }}">
                            Complete Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function () {
                window.history.pushState(null, "", window.location.href);
            };
        });
        $(document).ready(function () {
            $('#complete_order').click(function () {
                $('.loaderElement').show();
                $('.loaderElement').fadeOut(50000);
                $(this).css('pointer-events', 'none');
                $(this).attr('onclick', 'return false;');
                $('#complete_order').attr('disabled', true);
                window.location.href = $(this).attr('data-uri');
            });
            window.CommunicationHandler = {};

            function parseQueryString(str) {
                var vars = [];
                var arr = str.split('&');
                var pair;
                for (var i = 0; i < arr.length; i++) {
                    pair = arr[i].split('=');
                    vars[pair[0]] = unescape(pair[1]);
                }
                return vars;
            }

            window.CommunicationHandler.onReceiveCommunication = function (argument) {
                var params = parseQueryString(argument.qstr)
                switch (params['action']) {
                    case "resizeWindow":
                        console.log('resize');
                        break;
                    case "successfulSave":
                        console.log('save');
                        break;
                    case "cancel":
                        console.log('cancel');
                        break;
                    case "transactResponse" :
                        var transResponse = JSON.parse(params['response']);
                }
            }
            //send the token
            $('#send_hptoken').submit();
        });
    </script>
@endsection
