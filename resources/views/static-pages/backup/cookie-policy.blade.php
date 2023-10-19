@extends('static-pages.base.main')

@section('title') Cookie Policy @endsection

@section('css')

@endsection

@section('content')

    <section class="section section-light">
        <div class="container">
            {!! $site_setting->cookie_policy_page !!}
        </div>
    </section>

@endsection
