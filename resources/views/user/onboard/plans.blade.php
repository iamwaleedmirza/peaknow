@extends('static-pages.newBase.main')

@section('title') Plans @endsection

@section('meta_description')
    Starting as low as $32/month. Choose a Plan and take control of your sexual health today. Get your taladafil gummies with a monthly suscription and more
@endsection

@section('header')
    <header class="peaks pages-abt-us">
        <div class="overlay">
            <div class="navigation">
                <div class="container">
                    <div class="phone-devices">
                        <a class="navbar-brand-narrow" href="{{ route('account-home') }}">
                            <img src="{{ asset('logo/Logo-white.svg') }}" alt="Website Logo">
                        </a>
                    </div>
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="dev-cont-logo">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <a class="navbar-brand" href="{{ route('account-home') }}">
                                <img src="{{ asset('logo/Logo-white.svg') }}" alt="Website Logo">
                            </a>
                        </div>
                        <div class="dev-cont">
                            <img id="theme-toggle-mb" src="{{ asset('assets/sun.svg') }}" class="modes-btn" alt="dark light mode icons">
                            @if (auth()->user())
                                <a href="{{ route('account-home') }}" class="dss-btn nav-btn navbar-btn">Go to Dashboard</a>
                            @else
                                <a href="{{ route('plans') }}" class="dss-btn nav-btn navbar-btn">Choose Your Plan</a>
                            @endif
                        </div>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <div class="modes-cyp">
                                <img id="theme-toggle" src="{{ asset('assets/sun.svg') }}" class="modes-btn for-dt" alt="dark light mode icons">
                                <a href="{{ route('account-home') }}" class="dss-btn nav-btn for-dt navbar-btn">
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

        </div>
    </header>
@endsection

@section('css')
    <link href="{{ asset('/css/peaks/home.css') }}" rel="stylesheet"/>
@endsection

@section('content')

    @include('static-pages.newBase.components.section-plans')

@endsection
