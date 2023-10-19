@extends('static-pages.base.main')

@section('title') Terms & Conditions @endsection

@section('css')

@endsection

@section('content')

    <section class="section section-light">
        <div class="container">
            {!! $site_setting->terms_condition_page !!}
        </div>
    </section>

@endsection
