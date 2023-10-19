@extends('static-pages.base.main')

@section('title') Privacy Policy @endsection

@section('css')

@endsection

@section('content')

    <section class="section section-light">
        <div class="container">
            {!! $site_setting->privacy_policy_page !!}
        </div>
    </section>

@endsection
