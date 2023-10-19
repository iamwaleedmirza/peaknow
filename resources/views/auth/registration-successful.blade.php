@extends('user.base.main')
@section('title') Registration Successful @endsection

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
        <div class="card d-inline-block mx-3 p-3 p-md-4 mb-4">
            <div class="success-img-wrapper">
                <img src="{{ asset('images/svg/welcome.svg') }}">
            </div>
            <div class="text-center mb-4">
                <h4>Welcome to Peaks Curative!</h4>
                <p class="mb-0">Your account was successfully created.</p>
                <p>Please verify your phone number and email address on the next screens.</p>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3">
                <form id="continue-form" action="{{ route('user.register_successful.post') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-peaks continue-btn">
                        Continue <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>
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
