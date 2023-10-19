@extends('static-pages.newBase.main')

@section('title') Plans @endsection

@section('meta_description')
    Starting as low as $32/month. Choose a Plan and take control of your sexual health today. Get your taladafil gummies with a monthly suscription and more
@endsection

@section('header')
    <header class="peaks pages-abt-us">
        <div class="overlay">
            @include('static-pages.newBase.components.navbar')
        </div>
    </header>
@endsection

@section('css')
    <link href="{{ asset('/css/peaks/home.css') }}" rel="stylesheet"/>
@endsection

@section('content')

    @include('static-pages.newBase.components.section-plans')

@endsection
