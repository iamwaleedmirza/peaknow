@extends('user.base.main')

@section('title') Sorry we're not live in your state yet. @endsection

@section('css')
    <link href="{{ asset('css/peaks/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="section-login text-center">
        <div class="row justify-content-center mx-0 px-0">
            <div class="col-10 col-sm-8 col-md-6 col-lg-5">

                <img width="120" height="120" src="{{ asset('images/png/denied.png') }}" alt=""/>
                <h3 class="mt-4">Thank You for Your Interest</h3>
                <h6>We are sorry, Peaks Curative is not available for your state yet.</h6>
                <h6 class="mb-3 mt-3">
                    We are currently available in the following states: <br>
                    {{ $strAllowedStates }}
                </h6>
                <h6>If you reside in one of the above states <a href="{{ route('continue-state-not-live') }}" class="t-link">please continue</a></h6>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>

    </script>
@endsection
