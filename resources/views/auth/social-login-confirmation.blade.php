@extends('user.base.main')

@section('title') Social Login Confirmation @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">

@endsection

@section('content')
    <section class="section-login pt-0">
        <div class="row justify-content-center mx-0 px-0">

            <div class="col-12">
                <div class="row mx-0 px-0 justify-content-center">
                    <div class="card mw-450px p-3 p-md-5 mb-5">

                        <h5 class="text-center mb-3">Create your account</h5>
                        <p>We'll use your details from {{Session::get('userSocial')['provider']}} to create an account:</p>

                        <div class="mb-2">
                            <p class="mb-1 t-small">Email address</p>
                            <h6>{{Session::get('user')['email']}}</h6>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 t-small">Full name</p>
                            <h6>{{Session::get('user')['first_name']}} {{Session::get('user')['last_name']}}</h6>
                        </div>

                        <p class="mb-4 t-small">
                            By creating an account, I accept the
                            <a href="{{ route('terms-conditions') }}" target="_blank">
                                Terms of Service
                            </a> and acknowledge the <a href="{{ route('privacy-policy') }}" target="_blank">
                                Privacy Policy
                            </a>.
                        </p>

                        {{--<div class="text-center mb-3">
                            <form id="continue-form" method="POST" action="{{route('register-social-user')}}">
                                @csrf
                            <button type="submit" class="btn btn-peaks continue-btn">
                                Create your account
                            </button>
                            </form>
                        </div>--}}

                        <hr>

                        <a href="{{ route('login.user') }}" class="t-link text-center h-color">
                            Already have an Peaks account? Log in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
        });
    $(document).on('click', ".continue-btn", function() {
        // $(this).attr('disabled', 'true')
        $('#continue-form').submit();
        $('.loaderElement').show();
    });
    // setTimeout(() => {
    //     $('.loaderElement').hide();
    // }, 6000);
</script>
@endsection
