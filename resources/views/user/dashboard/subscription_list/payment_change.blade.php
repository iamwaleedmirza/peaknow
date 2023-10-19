@extends('user.base.main')
@section('title') Edit Payment @endsection
@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-md-8">
                <div id="iframe_holder" class="center-block" style="margin-bottom: 20px;" data-mediator="payment-form-loader">
                    <iframe id="load_payment" class="embed-responsive-item" name="load_payment" width="100%" height="600" scrolling="yes">
                    </iframe>
                    <form id="send_hptoken" action="{{config('services.authorize.edit_payment_profile_url')}}" method="post" target="load_payment">
                        <input type="hidden" name="token" value="{{ $token }}" />
                        <input type="hidden" name="paymentProfileId" value="{{ auth()->user()->payment_profile_id }}"/>
                    </form>
                    <a href="{{route('user.plan.index')}}"><button class="btn btn-light">Back To Dashboard</button></a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
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
                        logPaymentUpdated();
                        showAlert('success', 'Your payment method is updated successfully', function (response) {
                            window.location.href = '{{ route('user.plan.index' )}}';
                        });
                        break;
                    case "cancel":
                        console.log('cancel');
                        window.location.href = '{{ route('user.plan.index' )}}';
                        break;
                    case "transactResponse" :
                        var transResponse = JSON.parse(params['response']);
                        break;
                }
            }
            //send the token
            $('#send_hptoken').submit();

            function logPaymentUpdated() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('log.payment-updated') }}',
                    contentType: false,
                    cache: false,
                    beforeSend : function() {
                        $('.loaderElement').show();
                    },
                    success: (response) => {
                        $('.loaderElement').hide();
                    },
                    error: (error) => {
                        console.log(error)
                        $('.loaderElement').hide();
                    },
                });
            }

        });
    </script>
@endsection
