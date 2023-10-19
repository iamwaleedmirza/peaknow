@extends('user.base.main')
@section('title') Make Payment @endsection
@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-md-8">
                <div id="iframe_holder" class="center-block" style="margin-bottom: 20px;" data-mediator="payment-form-loader">
                    <iframe id="load_payment" class="embed-responsive-item" name="load_payment" width="100%" height="600" scrolling="yes" style="border: 2px solid #ddd">
                    </iframe>
                    <form id="send_hptoken" action="{{env('AUTHORIZE_FORM_ACTION','https://test.authorize.net/payment/payment')}}" method="post" target="load_payment">
                        <input type="hidden" name="token" value="{{ $token }}" />
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
     $(document).on('click', ".payButton", function() {


        });


        var iframe = $('#load_payment').contents();

        iframe.find(".payButton").click(function(){
            $('.loaderElement').show();
                $('.loaderElement').fadeOut(40000);
        });
        $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
        $(document).ready(function(){
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
                switch(params['action']){
                    case "resizeWindow":
                        console.log('resize');
                        break;
                    case "successfulSave":
                        console.log('save');
                        break;
                    case "cancel":
                        var url = "/user/account/home";
                        window.location.href = url;
                        console.log('cancel');
                        break;
                    case "transactResponse" :
                        var transResponse = JSON.parse(params['response']);
                        var transaction_id = transResponse.transId;
                        if (transaction_id && transaction_id !== null && transaction_id !== '') {
                            var url = "/user/payment/success?order_id="+'{{$order_id}}'+"&transaction_id="+transaction_id;
                            window.location.href = url;
                        }else{
                            var url = "{{route('payment.failed')}}";
                            window.location.href = url;
                        }

                }
            }
            //send the token
            $('#send_hptoken').submit();
        });
    </script>
@endsection
