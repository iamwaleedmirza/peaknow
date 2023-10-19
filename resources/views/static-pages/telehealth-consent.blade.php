@extends('static-pages.newBase.main')

@section('title') Telehealth Consent @endsection

@section('css')

@endsection

@section('header')
    <header class="peaks pages-abt-us">
        <div class="overlay">
            @include('static-pages.newBase.components.navbar')
        </div>
    </header>
@endsection

@section('content')

    <section class="section section-light">
        <div class="container">
            {!! $site_setting->telehealth_consent_page !!}
        </div>
    </section>

@endsection
